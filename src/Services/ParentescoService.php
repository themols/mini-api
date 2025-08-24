<?php
namespace App\Services;

use App\Repositories\ParentescoRepository;
use App\Validators\ParentescoValidator;
use Exception;

class ParentescoService
{
    private ParentescoRepository $repository;
    private ParentescoValidator $validator;

    public function __construct(ParentescoRepository $repository, ParentescoValidator $validator)
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
