<?php
namespace App\Services;

use App\Repositories\EstadoRepository;
use App\Validators\EstadoValidator;
use Exception;
use App\Security\Security;

Security::check();

class EstadoService
{
    private EstadoRepository $repository;
    private EstadoValidator $validator;

    public function __construct(EstadoRepository $repository, EstadoValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Retorna todos os estados
     */
    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    /**
     * Retorna um estado pelo ID
     */
    public function getById(int $id): ?array
    {
        return $this->repository->getById($id);
    }

    /**
     * Cria um novo estado após validar os dados
     */
    public function create(array $data): array
    {
        $errors = $this->validator->validate($data);
        if (!empty($errors)) {
            throw new Exception(json_encode(['errors' => $errors]));
        }

        return $this->repository->create($data);
    }

    /**
     * Atualiza um estado existente após validação
     */
    public function update(int $id, array $data): ?array
    {
        $errors = $this->validator->validate($data);
        if (!empty($errors)) {
            throw new Exception(json_encode(['errors' => $errors]));
        }

        return $this->repository->update($id, $data);
    }

    /**
     * Deleta um estado pelo ID
     */
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
