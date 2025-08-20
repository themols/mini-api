<?php
namespace App\Controllers;

use App\Middleware\JwtMiddleware;
use App\Services\UserService;

class UserController
{
    private $f3;
    private $service;

    public function __construct(\Base $f3)
    {
        $this->f3 = $f3;
        $this->service = new UserService($f3->get('DB'));
    }

    public function index()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['users.read'])) return;

        echo json_encode($this->service->getAllUsers());
    }

    public function show($f3, $args)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['users.read'])) return;

        $user = $this->service->getUserById((int)$args['id']);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        echo json_encode($user);
    }

    public function create()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['users.create'])) return;

        $data = json_decode($this->f3->get('BODY'), true) ?: [];
        $id = $this->service->createUser($data);

        http_response_code(201);
        echo json_encode(['id' => $id]);
    }

    public function update($f3, $args)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['users.update'])) return;

        $data = json_decode($this->f3->get('BODY'), true) ?: [];
        $id = $this->service->updateUser((int)$args['id'], $data);

        if (!$id) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        echo json_encode(['id' => $id]);
    }

    public function delete($f3, $args)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['users.delete'])) return;

        $success = $this->service->deleteUser((int)$args['id']);
        if (!$success) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        echo json_encode(['deleted' => true]);
    }
}
