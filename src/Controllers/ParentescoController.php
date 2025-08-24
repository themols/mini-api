<?php
namespace App\Controllers;

use App\Services\ParentescoService;
use App\Validators\ParentescoValidator;
use App\Middleware\JwtMiddleware;

class ParentescoController
{
    private $service;
    private $f3;

    public function __construct($f3)
    {
        $this->f3 = $f3;
        $repository = new \App\Repositories\ParentescoRepository($f3->get('DB'));
        $validator = new ParentescoValidator();
        $this->service = new ParentescoService($repository, $validator);
    }

    public function index()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['parentescos.read'])) return;
        echo json_encode($this->service->getAll());
    }

    public function show($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['parentescos.read'])) return;

        $parentesco = $this->service->getById((int)$id);
        if (!$parentesco) {
            http_response_code(404);
            echo json_encode(['error' => 'Parentesco não encontrado']);
            return;
        }
        echo json_encode($parentesco);
    }

    public function create()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['parentescos.create'])) return;

        $data = (array) json_decode(file_get_contents('php://input'), true);
        try {
            $parentesco = $this->service->create($data);
            echo json_encode($parentesco);
        } catch (\Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function update($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['parentescos.update'])) return;

        $data = (array) json_decode(file_get_contents('php://input'), true);
        try {
            $parentesco = $this->service->update((int)$id, $data);
            if (!$parentesco) {
                http_response_code(404);
                echo json_encode(['error' => 'Parentesco não encontrado']);
                return;
            }
            echo json_encode($parentesco);
        } catch (\Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function delete($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['parentescos.delete'])) return;

        if (!$this->service->delete((int)$id)) {
            http_response_code(404);
            echo json_encode(['error' => 'Parentesco não encontrado']);
            return;
        }
        echo json_encode(['success' => true]);
    }
}
