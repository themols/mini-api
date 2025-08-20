<?php
namespace App\Controllers;

use App\Models\UserRole;
use App\Models\User;
use App\Models\Role;
use App\Middleware\JwtMiddleware;

/**
 * Class UserRoleController
 * Manage user_roles assignments
 * @package App\Controllers
 */
class UserRoleController
{
    private $f3;
    public function __construct(\Base $f3) { $this->f3 = $f3; }

    /**
     * GET /user-roles
     */
    public function index()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['userrole.read'])) return;
        $db = $this->f3->get('DB');
        $rows = $db->exec('SELECT ur.user_id, u.username, ur.role_id, r.name as role_name FROM user_roles ur JOIN users u ON ur.user_id=u.id JOIN roles r ON ur.role_id=r.id');
        echo json_encode($rows);
    }

    /**
     * GET /user-roles/user/@user_id
     */
    public function listByUser($f3, $args)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['userrole.read'])) return;
        $userId = (int)$args['user_id'];
        $db = $this->f3->get('DB');
        $rows = $db->exec('SELECT r.* FROM roles r JOIN user_roles ur ON r.id=ur.role_id WHERE ur.user_id=?', [$userId]);
        echo json_encode($rows);
    }

    /**
     * POST /user-roles { user_id, role_id }
     */
    public function assign()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['userrole.assign'])) return;
        $body = json_decode($this->f3->get('BODY'), true) ?: [];
        $userId = (int)($body['user_id'] ?? 0);
        $roleId = (int)($body['role_id'] ?? 0);
        if (!$userId || !$roleId) { http_response_code(400); echo json_encode(['error'=>'user_id and role_id required']); return; }
        $db = $this->f3->get('DB');
        $db->exec('INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)', [$userId, $roleId]);
        echo json_encode(['assigned'=>true,'user_id'=>$userId,'role_id'=>$roleId]);
    }

    /**
     * DELETE /user-roles/user/@user_id/role/@role_id
     */
    public function remove($f3, $args)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['userrole.remove'])) return;
        $userId = (int)$args['user_id'];
        $roleId = (int)$args['role_id'];
        $db = $this->f3->get('DB');
        $db->exec('DELETE FROM user_roles WHERE user_id=? AND role_id=?', [$userId, $roleId]);
        echo json_encode(['removed'=>true,'user_id'=>$userId,'role_id'=>$roleId]);
    }
}
