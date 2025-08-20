<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

/**
 * Class JwtMiddleware
 * Valida JWT e anexa usuário e permissões ao Hive.
 *
 * @package App\Middleware
 */
class JwtMiddleware
{
    /**
     * Requer autenticação e permissões opcionais.
     *
     * @param \Base $f3
     * @param array $requiredPermissions
     * @return bool true se autorizado, caso contrário envia erro e sai
     */
    public static function requireAuth(\Base $f3, array $requiredPermissions = []): bool
    {
        // Captura do header Authorization de forma segura
        $hdr = $f3->get('HEADERS.Authorization') ?? $f3->get('SERVER.HTTP_AUTHORIZATION');
        if (!$hdr || strpos($hdr, 'Bearer ') !== 0) {
            http_response_code(401);
            echo json_encode(['error' => 'Missing or invalid Authorization header']);
            return false;
        }

        $jwt = substr($hdr, 7);

        // Carrega configuração corretamente
        $configPath = __DIR__ . '/../../config/config.php';
        if (!file_exists($configPath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Config file not found']);
            return false;
        }

        $config = require $configPath;

        if (!isset($config['jwt']['secret'])) {
            http_response_code(500);
            echo json_encode(['error' => 'JWT secret not configured']);
            return false;
        }

        $jwtSecret = $config['jwt']['secret'];

        try {
            $decoded = JWT::decode($jwt, new Key($jwtSecret, 'HS256'));
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
            return false;
        }

        $sub = $decoded->sub ?? null; // sub vem do payload
        if (!$sub) {
            http_response_code(401);
            echo json_encode(['error' => 'Malformed token']);
            return false;
        }

        $userModel = new User($f3->get('DB'));
        $userModel->load(['id=?', (int)$sub]);
        if ($userModel->dry()) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return false;
        }

        $roles = array_map(fn($r) => $r['name'], $userModel->getRoles());
        $perms = array_map(fn($p) => $p['name'], $userModel->getPermissions());

        $f3->set('user', [
            'id' => (int)$userModel->id,
            'username' => $userModel->username,
            'roles' => $roles,
            'permissions' => $perms
        ]);


        foreach ($requiredPermissions as $perm) {
            if (!in_array($perm, $perms)) {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                return false;
            }
        }

        return true;
    }
}
