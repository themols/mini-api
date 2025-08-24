<?php
namespace App\Controllers;

use App\Services\EstadoService;
use App\Validators\EstadoValidator;
use App\Middleware\JwtMiddleware;
use App\Security\Security;

Security::check();
class EstadoController
{
    private $service;
    private $f3;

    public function __construct($f3)
    {
        $this->f3 = $f3;
        $this->service = new EstadoService();
    }

    public function index()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['estado.read'])) return;

        echo json_encode($this->service->getAll());
    }

    public function show($params)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['estado.read'])) return;

        $estado = $this->service->getById($params['id']);
        if ($estado) {
            echo json_encode($estado);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Estado não encontrado']);
        }
    }

    public function create()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['estado.create'])) return;

        $data = json_decode(file_get_contents("php://input"), true);

        // Validação
        $errors = EstadoValidator::validate($data);
        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $estado = $this->service->create($data);
        http_response_code(201);
        echo json_encode($estado);
    }

    public function update($params)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['estado.update'])) return;

        $data = json_decode(file_get_contents("php://input"), true);

        // Validação
        $errors = EstadoValidator::validate($data);
        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $estado = $this->service->update($params['id'], $data);
        if ($estado) {
            echo json_encode($estado);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Estado não encontrado']);
        }
    }

    public function delete($params)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['estado.delete'])) return;

        if ($this->service->delete($params['id'])) {
            echo json_encode(['message' => 'Estado excluído com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Estado não encontrado']);
        }
    }
}
