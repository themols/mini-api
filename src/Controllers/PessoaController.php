<?php
namespace App\Controllers;

use App\Services\PessoaService;
use App\Validators\PessoaValidator;
use App\Middleware\JwtMiddleware;
use App\Security\Security;

Security::check();

class PessoaController
{
    private $service;
    private $f3;

    public function __construct($f3, PessoaService $service)
    {
        $this->service = $service;
        $this->f3 = $f3;
    }

    public function index()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['pessoa.read'])) return;

        echo json_encode($this->service->getAll());
    }

    public function show($f3, $params)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['pessoa.read'])) return;

        $pessoa = $this->service->getById((int)$params['id']);
        if (!$pessoa) {
            http_response_code(404);
            echo json_encode(["error" => "Pessoa não encontrada"]);
            return;
        }

        echo json_encode($pessoa);
    }

    public function store()
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['pessoa.create'])) return;

        $data = json_decode(file_get_contents('php://input'), true);
        $errors = PessoaValidator::validate($data);

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(["errors" => $errors]);
            return;
        }

        $id = $this->service->create($data);
        echo json_encode(["message" => "Pessoa criada com sucesso", "id" => $id]);
    }

    public function update($f3, $params)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['pessoa.update'])) return;

        $data = json_decode(file_get_contents('php://input'), true);
        $errors = PessoaValidator::validate($data);

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(["errors" => $errors]);
            return;
        }

        $success = $this->service->update((int)$params['id'], $data);
        if (!$success) {
            http_response_code(404);
            echo json_encode(["error" => "Pessoa não encontrada"]);
            return;
        }

        echo json_encode(["message" => "Pessoa atualizada com sucesso"]);
    }

    public function destroy($f3, $params)
    {
        if (!JwtMiddleware::requireAuth($this->f3, ['pessoa.delete'])) return;

        $success = $this->service->delete((int)$params['id']);
        if (!$success) {
            http_response_code(404);
            echo json_encode(["error" => "Pessoa não encontrada"]);
            return;
        }

        echo json_encode(["message" => "Pessoa excluída com sucesso"]);
    }
}
