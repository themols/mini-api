<?php
namespace App\Repositories;

use App\Models\Instrucao;
use App\Security\Security;

Security::check();

class InstrucaoRepository
{
    private Instrucao $model;

    public function __construct(\DB\SQL $db)
    {
        $this->model = new Instrucao($db);
    }

    public function getAll(): array
    {
        return $this->model->find();
    }

    public function getById(int $id): ?array
    {
        $this->model->load(['id=?', $id]);
        return $this->model->dry() ? null : $this->model->cast();
    }

    public function create(array $data): array
    {
        $this->model->copyfrom($data);
        $this->model->save();
        return $this->model->cast();
    }

    public function update(int $id, array $data): ?array
    {
        $this->model->load(['id=?', $id]);
        if ($this->model->dry()) return null;

        $this->model->copyfrom($data);
        $this->model->save();
        return $this->model->cast();
    }

    public function delete(int $id): bool
    {
        $this->model->load(['id=?', $id]);
        if ($this->model->dry()) return false;

        $this->model->erase();
        return true;
    }
}
