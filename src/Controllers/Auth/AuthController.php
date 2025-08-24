<?php
namespace App\Controllers\Auth;

use App\Services\AuthService;
use App\Models\User;
use App\Models\RefreshToken;

/**
 * Class AuthController
 *
 * Authentication endpoints (login, refresh, logout)
 *
 * @package App\Controllers
 */
class AuthController
{
    private $f3;
    private $auth;

    public function __construct(\Base $f3)
    {
        $this->f3 = $f3;
        $this->auth = new AuthService($f3);
    }

    /**
     * POST /auth/login
     * Body: { username, password }
     */
    public function login()
    {
        $body = json_decode($this->f3->get('BODY'), true) ?: [];
        $username = $body['username'] ?? '';
        $password = $body['password'] ?? '';

        $user = new User($this->f3->get('DB'));
        $user->load(['username=?', $username]);
        if ($user->dry() || !$user->verifyPassword($password)) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }

        $payload = [
            'sub' => (int)$user->id,
            'email' => $user->email,
            'username' => $user->username
        ];

        $access = $this->auth->generateAccessToken($payload);
        $refresh = $this->auth->generateRefreshToken((int)$user->id);

        echo json_encode([
            'access_token' => $access,
            'refresh_token' => $refresh,
            'token_type' => 'Bearer',
            'expires_in' => (int)$this->f3->get('jwt.exp')
        ]);
    }

    /**
     * POST /auth/refresh
     * Body: { refresh_token }
     */
    public function refresh()
    {
        $body = json_decode($this->f3->get('BODY'), true) ?: [];
        $token = $body['refresh_token'] ?? '';

        if (!$token) {
            http_response_code(400);
            echo json_encode(['error' => 'refresh_token required']);
            return;
        }

        $rt = new RefreshToken($this->f3->get('DB'));
        $rt->load(['token=?', $token]);
        if ($rt->dry()) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid refresh token']);
            return;
        }

        if (strtotime($rt->expires_at) < time()) {
            $rt->erase();
            http_response_code(401);
            echo json_encode(['error' => 'Refresh token expired']);
            return;
        }

        $user = new User($this->f3->get('DB'));
        $user->load(['id=?', $rt->user_id]);
        if ($user->dry()) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        $newRefresh = $this->auth->rotateRefreshToken($rt);
        $payload = ['sub' => (int)$user->id, 'email' => $user->email];
        $access = $this->auth->generateAccessToken($payload);

        echo json_encode([
            'access_token' => $access,
            'refresh_token' => $newRefresh,
            'token_type' => 'Bearer',
            'expires_in' => (int)$this->f3->get('jwt.exp')
        ]);
    }

    /**
     * POST /auth/logout
     * Body: { refresh_token }
     */
    public function logout()
    {
        $body = json_decode($this->f3->get('BODY'), true) ?: [];
        $token = $body['refresh_token'] ?? '';
        if ($token) {
            $this->auth->revokeRefreshToken($token);
        }
        echo json_encode(['message' => 'Logged out']);
    }
}
