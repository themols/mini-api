<?php
namespace App\Services;

use App\Security\Security;

Security::check();

class InformacoesComplementaresService {

    private $repository;

    public function __construct(InformacoesComplementaresRepository $repository) {
        $this->repository = $repository;
    }

    public function getAll() {
        return $this->repository->findAll();
    }

    public function getById($id) {
        return $this->repository->findById($id);
    }

    public function getByPessoaId($pessoaId) {
        return $this->repository->findByPessoaId($pessoaId);
    }

    public function create($data) {
        $errors = InformacoesComplementaresValidator::validate($data);
        if (!empty($errors)) {
            throw new Exception(json_encode($errors));
        }
        return $this->repository->save($data);
    }

    public function update($id, $data) {
        $errors = InformacoesComplementaresValidator::validate($data);
        if (!empty($errors)) {
            throw new Exception(json_encode($errors));
        }
        return $this->repository->update($id, $data);
    }

    public function delete($id) {
        $this->repository->delete($id);
    }
}
