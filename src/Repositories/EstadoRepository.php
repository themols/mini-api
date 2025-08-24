<?php
namespace App\Repositories;

use App\Models\Estado;
use DB\SQL;

class EstadoRepository
{
    private Estado $model;

    public function __construct(SQL $db)
    {
        $this->model = new Estado($db);
    }

    /**
     * Retorna todos os estados
     */
    public function getAll(): array
    {
        return $this->model->find();
    }

    /**
     * Retorna um estado pelo ID
     */
    public function getById(int $id): ?array
    {
        $estado = $this->model->load(['id = ?', $id]);
        return $estado->dry() ? null : $estado->cast();
    }

    /**
     * Cria um novo estado
     */
    public function create(array $data): array
    {
        $this->model->reset();
        $this->model->copyFrom($data);
        $this->model->save();
        return $this->model->cast();
    }

    /**
     * Atualiza um estado existente
     */
    public function update(int $id, array $data): ?array
    {
        $estado = $this->model->load(['id = ?', $id]);
        if ($estado->dry()) {
            return null;
        }

        $estado->copyFrom($data);
        $estado->save();
        return $estado->cast();
    }

    /**
     * Deleta um estado pelo ID
     */
    public function delete(int $id): bool
    {
        $estado = $this->model->load(['id = ?', $id]);
        if ($estado->dry()) {
            return false;
        }

        $estado->erase();
        return true;
    }
}
