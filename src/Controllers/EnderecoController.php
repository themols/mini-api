<?php
namespace App\Controllers;

use App\Services\EnderecoService;
use App\Validators\EnderecoValidator;
use App\Middleware\JwtMiddleware;
use App\Security\Security;

Security::check();
class EnderecoController
{
    private $service;
    private $f3;

    public function __construct($f3)
    {
        $this->f3 = $f3;
        $repository = new \App\Repositories\EnderecoRepository($f3->get('DB'));
        $validator = new EnderecoValidator();
        $this->service = new EnderecoService($repository, $validator);
    }

    public function index()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['endereco.read'])) return;
        echo json_encode($this->service->getAll());
    }

    public function show($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['endereco.read'])) return;

        $endereco = $this->service->getById((int)$id);
        if (!$endereco) {
            http_response_code(404);
            echo json_encode(['error' => 'Endereço não encontrado']);
            return;
        }
        echo json_encode($endereco);
    }

    public function create()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['endereco.create'])) return;

        $data = (array) json_decode(file_get_contents('php://input'), true);
        try {
            $endereco = $this->service->create($data);
            echo json_encode($endereco);
        } catch (\Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function update($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['endereco.update'])) return;

        $data = (array) json_decode(file_get_contents('php://input'), true);
        try {
            $endereco = $this->service->update((int)$id, $data);
            if (!$endereco) {
                http_response_code(404);
                echo json_encode(['error' => 'Endereço não encontrado']);
                return;
            }
            echo json_encode($endereco);
        } catch (\Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function delete($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['endereco.delete'])) return;

        if (!$this->service->delete((int)$id)) {
            http_response_code(404);
            echo json_encode(['error' => 'Endereço não encontrado']);
            return;
        }
        echo json_encode(['success' => true]);
    }
}
