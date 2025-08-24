<?php
class InformacoesComplementaresRepository {

    private $db;
    private $mapper;

    public function __construct(\DB\SQL $db) {
        $this->db = $db;
        $this->mapper = new InformacoesComplementares($db);
    }

    public function findAll() {
        return $this->mapper->find();
    }

    public function findById($id) {
        return $this->mapper->load(['id = ?', $id]);
    }

    public function findByPessoaId($pessoaId) {
        return $this->mapper->find(['pessoa_id = ?', $pessoaId]);
    }

    public function save($data) {
        $this->mapper->copyFrom($data);
        $this->mapper->save();
        return $this->mapper->cast();
    }

    public function update($id, $data) {
        $this->mapper->load(['id = ?', $id]);
        $this->mapper->copyFrom($data);
        $this->mapper->update();
        return $this->mapper->cast();
    }

    public function delete($id) {
        $this->mapper->load(['id = ?', $id]);
        $this->mapper->erase();
    }
}
