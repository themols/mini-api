<?php
namespace App\Controllers;

use App\Security\Security;

Security::check();

class InformacoesComplementaresController {

    private $service;

    public function __construct(InformacoesComplementaresService $service) {
        $this->service = $service;
    }

    public function index($f3) {
        echo json_encode($this->service->getAll());
    }

    public function show($f3, $params) {
        $item = $this->service->getById($params['id']);
        if (!$item->id) {
            $f3->error(404, 'Informação complementar não encontrada');
        }
        echo json_encode($item);
    }

    public function create($f3) {
        $data = json_decode($f3->get('BODY'), true);
        try {
            $result = $this->service->create($data);
            $f3->status(201);
            echo json_encode($result);
        } catch (Exception $e) {
            $f3->error(400, $e->getMessage());
        }
    }

    public function update($f3, $params) {
        $data = json_decode($f3->get('BODY'), true);
        try {
            $result = $this->service->update($params['id'], $data);
            echo json_encode($result);
        } catch (Exception $e) {
            $f3->error(400, $e->getMessage());
        }
    }

    public function delete($f3, $params) {
        $this->service->delete($params['id']);
        $f3->status(204);
    }
}
