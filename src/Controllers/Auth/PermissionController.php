<?php
namespace App\Controllers\Auth;

use App\Models\Permission;
use App\Middleware\JwtMiddleware;
use App\Security\Security;

Security::check();
/**
 * Class PermissionController
 * CRUD de permissões
 * @package App\Controllers
 */
class PermissionController
{
    private $f3;
    public function __construct(\Base $f3) { $this->f3 = $f3; }

    public function index()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['permission.read'])) return;
        $m = new Permission($this->f3->get('DB'));
        echo json_encode($m->find());
    }

    public function show($f3, $args)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['permission.read'])) return;
        $id = (int)$args['id'];
        $m = new Permission($this->f3->get('DB'));
        $m->load(['id=?',$id]);
        if ($m->dry()) { http_response_code(404); echo json_encode(['error'=>'Permission not found']); return; }
        echo json_encode(['id'=>(int)$m->id,'name'=>$m->name]);
    }

    public function create()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['permission.create'])) return;
        $body = json_decode($this->f3->get('BODY'), true) ?: [];
        $m = new Permission($this->f3->get('DB'));
        $m->name = $body['name'] ?? '';
        $m->save();
        http_response_code(201); echo json_encode(['id'=>(int)$m->id]);
    }

    public function update($f3, $args)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['permission.update'])) return;
        $id = (int)$args['id'];
        $body = json_decode($this->f3->get('BODY'), true) ?: [];
        $m = new Permission($this->f3->get('DB'));
        $m->load(['id=?',$id]);
        if ($m->dry()) { http_response_code(404); echo json_encode(['error'=>'Permission not found']); return; }
        if (isset($body['name'])) $m->name = $body['name'];
        $m->save();
        echo json_encode(['id'=>(int)$m->id]);
    }

    public function delete($f3, $args)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['permission.delete'])) return;
        $id = (int)$args['id'];
        $m = new Permission($this->f3->get('DB'));
        $m->load(['id=?',$id]);
        if ($m->dry()) { http_response_code(404); echo json_encode(['error'=>'Permission not found']); return; }
        $m->erase();
        echo json_encode(['deleted'=>true]);
    }
}
