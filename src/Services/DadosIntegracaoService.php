<?php
namespace App\Services;

use App\Repositories\DadosIntegracaoRepository;
use App\Validators\DadosIntegracaoValidator;
use Exception;

class DadosIntegracaoService
{
    private DadosIntegracaoRepository $repository;
    private DadosIntegracaoValidator $validator;

    public function __construct(DadosIntegracaoRepository $repository, DadosIntegracaoValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): ?array
    {
        return $this->repository->getById($id);
    }

    public function create(array $data): array
    {
        $errors = $this->validator->validate($data);
        if (!empty($errors)) {
            throw new Exception(json_encode(['errors' => $errors]));
        }
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): ?array
    {
        $errors = $this->validator->validate($data);
        if (!empty($errors)) {
            throw new Exception(json_encode(['errors' => $errors]));
        }
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
