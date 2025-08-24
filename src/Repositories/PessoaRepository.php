<?php
namespace App\Repositories;

use App\Models\Pessoa;
use DB\SQL;

class PessoaRepository
{
    private SQL $db;

    public function __construct(SQL $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $mapper = new Pessoa($this->db);
        $rows = $mapper->find();
        return array_map(fn($row) => $row->cast(), $rows);
    }

    public function findById(int $id): ?Pessoa
    {
        $pessoa = new Pessoa($this->db);
        $pessoa->load(['id = ?', $id]);
        return $pessoa->dry() ? null : $pessoa;
    }

    public function create(array $data): Pessoa
    {
        $pessoa = new Pessoa($this->db);
        $pessoa->setFromArray($data);
        $pessoa->save();
        return $pessoa;
    }

    public function update(int $id, array $data): ?Pessoa
    {
        $pessoa = $this->findById($id);
        if (!$pessoa) {
            return null;
        }
        $pessoa->setFromArray($data);
        $pessoa->save();
        return $pessoa;
    }

    public function delete(int $id): bool
    {
        $pessoa = $this->findById($id);
        if (!$pessoa) {
            return false;
        }
        $pessoa->erase();
        return true;
    }
}
