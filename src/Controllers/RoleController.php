<?php
namespace App\Controllers;

use App\Models\Role;
use App\Middleware\JwtMiddleware;

/**
 * Class RoleController
 * CRUD de roles
 * @package App\Controllers
 */
class RoleController
{
    private $f3;
    public function __construct(\Base $f3) { $this->f3 = $f3; }

    public function index()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['role.read'])) return;
        $m = new Role($this->f3->get('DB'));
        echo json_encode($m->find());
    }

    public function show($f3, $args)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['role.read'])) return;
        $id = (int)$args['id'];
        $m = new Role($this->f3->get('DB'));
        $m->load(['id=?',$id]);
        if ($m->dry()) { http_response_code(404); echo json_encode(['error'=>'Role not found']); return; }
        $perms = array_map(function($x){ return $x['name']; }, $m->getPermissions());
        echo json_encode(['id'=>(int)$m->id,'name'=>$m->name,'permissions'=>$perms]);
    }

    public function create()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['role.create'])) return;
        $body = json_decode($this->f3->get('BODY'), true) ?: [];
        $m = new Role($this->f3->get('DB'));
        $m->name = $body['name'] ?? '';
        $m->save();
        if (!empty($body['permissions']) && is_array($body['permissions'])) $m->setPermissions($body['permissions']);
        http_response_code(201); echo json_encode(['id'=>(int)$m->id]);
    }

    public function update($f3, $args)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['role.update'])) return;
        $id = (int)$args['id'];
        $body = json_decode($this->f3->get('BODY'), true) ?: [];
        $m = new Role($this->f3->get('DB'));
        $m->load(['id=?',$id]);
        if ($m->dry()) { http_response_code(404); echo json_encode(['error'=>'Role not found']); return; }
        if (isset($body['name'])) $m->name = $body['name'];
        $m->save();
        if (isset($body['permissions']) && is_array($body['permissions'])) $m->setPermissions($body['permissions']);
        echo json_encode(['id'=>(int)$m->id]);
    }

    public function delete($f3, $args)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['role.delete'])) return;
        $id = (int)$args['id'];
        $m = new Role($this->f3->get('DB'));
        $m->load(['id=?',$id]);
        if ($m->dry()) { http_response_code(404); echo json_encode(['error'=>'Role not found']); return; }
        $m->erase();
        echo json_encode(['deleted'=>true]);
    }
}
