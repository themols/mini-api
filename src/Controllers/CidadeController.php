<?php
namespace App\Controllers;

use App\Services\CidadeService;
use App\Validators\CidadeValidator;
use App\Middleware\JwtMiddleware;

class CidadeController
{
    private $service;
    private $f3;

    public function __construct($f3)
    {
        $this->f3 = $f3;
        $repository = new \App\Repositories\CidadeRepository($f3->get('DB'));
        $validator = new CidadeValidator();
        $this->service = new CidadeService($repository, $validator);
    }

    public function index()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['cidade.read'])) return;
        echo json_encode($this->service->getAll());
    }

    public function show($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['cidade.read'])) return;

        $cidade = $this->service->getById((int)$id);
        if (!$cidade) {
            http_response_code(404);
            echo json_encode(['error' => 'Cidade não encontrada']);
            return;
        }
        echo json_encode($cidade);
    }

    public function create()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['cidade.create'])) return;

        $data = (array) json_decode(file_get_contents('php://input'), true);
        try {
            $cidade = $this->service->create($data);
            echo json_encode($cidade);
        } catch (\Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function update($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['cidade.update'])) return;

        $data = (array) json_decode(file_get_contents('php://input'), true);
        try {
            $cidade = $this->service->update((int)$id, $data);
            if (!$cidade) {
                http_response_code(404);
                echo json_encode(['error' => 'Cidade não encontrada']);
                return;
            }
            echo json_encode($cidade);
        } catch (\Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function delete($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['cidade.delete'])) return;

        if (!$this->service->delete((int)$id)) {
            http_response_code(404);
            echo json_encode(['error' => 'Cidade não encontrada']);
            return;
        }
        echo json_encode(['success' => true]);
    }
}
