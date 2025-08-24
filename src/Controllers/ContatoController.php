<?php
namespace App\Controllers;

use App\Services\ContatoService;
use App\Validators\ContatoValidator;
use App\Middleware\JwtMiddleware;

class ContatoController
{
    private $service;
    private $f3;

    public function __construct($f3)
    {
        $this->f3 = $f3;
        $repository = new \App\Repositories\ContatoRepository($f3->get('DB'));
        $validator = new ContatoValidator();
        $this->service = new ContatoService($repository, $validator);
    }

    public function index()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['contato.read'])) return;
        echo json_encode($this->service->getAll());
    }

    public function show($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['contato.read'])) return;

        $contato = $this->service->getById((int)$id);
        if (!$contato) {
            http_response_code(404);
            echo json_encode(['error' => 'Contato não encontrado']);
            return;
        }
        echo json_encode($contato);
    }

    public function create()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['contato.create'])) return;

        $data = (array) json_decode(file_get_contents('php://input'), true);
        try {
            $contato = $this->service->create($data);
            echo json_encode($contato);
        } catch (\Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function update($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['contato.update'])) return;

        $data = (array) json_decode(file_get_contents('php://input'), true);
        try {
            $contato = $this->service->update((int)$id, $data);
            if (!$contato) {
                http_response_code(404);
                echo json_encode(['error' => 'Contato não encontrado']);
                return;
            }
            echo json_encode($contato);
        } catch (\Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function delete($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['contato.delete'])) return;

        if (!$this->service->delete((int)$id)) {
            http_response_code(404);
            echo json_encode(['error' => 'Contato não encontrado']);
            return;
        }
        echo json_encode(['success' => true]);
    }
}
