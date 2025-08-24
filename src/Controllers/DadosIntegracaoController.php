<?php
namespace App\Controllers;

use App\Services\DadosIntegracaoService;
use App\Validators\DadosIntegracaoValidator;
use App\Middleware\JwtMiddleware;
use App\Security\Security;

Security::check();
class DadosIntegracaoController
{
    private $service;
    private $f3;

    public function __construct($f3)
    {
        $this->f3 = $f3;
        $repository = new \App\Repositories\DadosIntegracaoRepository($f3->get('DB'));
        $validator = new DadosIntegracaoValidator();
        $this->service = new DadosIntegracaoService($repository, $validator);
    }

    public function index()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['dados_integracao.read'])) return;
        echo json_encode($this->service->getAll());
    }

    public function show($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['dados_integracao.read'])) return;

        $item = $this->service->getById((int)$id);
        if (!$item) {
            http_response_code(404);
            echo json_encode(['error' => 'Registro não encontrado']);
            return;
        }
        echo json_encode($item);
    }

    public function create()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['dados_integracao.create'])) return;

        $data = (array) json_decode(file_get_contents('php://input'), true);
        try {
            $item = $this->service->create($data);
            echo json_encode($item);
        } catch (\Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function update($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['dados_integracao.update'])) return;

        $data = (array) json_decode(file_get_contents('php://input'), true);
        try {
            $item = $this->service->update((int)$id, $data);
            if (!$item) {
                http_response_code(404);
                echo json_encode(['error' => 'Registro não encontrado']);
                return;
            }
            echo json_encode($item);
        } catch (\Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function delete($id)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['dados_integracao.delete'])) return;

        if (!$this->service->delete((int)$id)) {
            http_response_code(404);
            echo json_encode(['error' => 'Registro não encontrado']);
            return;
        }
        echo json_encode(['success' => true]);
    }
}
