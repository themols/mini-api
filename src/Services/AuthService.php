<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\RefreshToken;

class AuthService
{
    private $f3;
    private $db;
    private $jwtSecret;
    private $jwtExp;
    private $refreshExp;

    public function __construct(\Base $f3)
    {
        $this->f3 = $f3;
        $this->db = $f3->get('DB');

        $config = require __DIR__ . '/../config/config.php';
      
        $this->jwtSecret = $config['jwt']['secret'];
        $this->jwtExp = $config['jwt']['exp'] ?? 3600;
        $this->refreshExp = $config['jwt']['refresh_exp'] ?? 1209600;
    }

    /**
     * Gera um token de acesso JWT
     */
    public function generateAccessToken(array $payload): string
    {
        $now = time();

        $token = [
            'iat' => $now,
            'exp' => $now + $this->jwtExp,
            'sub' => $payload['sub'],
            'email' => $payload['email'] ?? null
        ];

        return JWT::encode($token, $this->jwtSecret, 'HS256');
    }

    /**
     * Gera um refresh token e salva no banco
     */
    public function generateRefreshToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + $this->refreshExp);

        $rt = new RefreshToken($this->db);

        $rt->token = $token;
        $rt->user_id = $userId;
        $rt->expires_at = $expires;
        $rt->save();

        return $token;
    }

    /**
     * Rotaciona um refresh token existente
     */
    public function rotateRefreshToken(RefreshToken $rt): string
    {
        $rt->erase();

        return $this->generateRefreshToken($rt->user_id);
    }

    /**
     * Revoga refresh token
     */
    public function revokeRefreshToken(string $token): void
    {
        $rt = new RefreshToken($this->db);

        $rt->load(['token=?', $token]);

        if (!$rt->dry()) {
            $rt->erase();
        }
    }
}
