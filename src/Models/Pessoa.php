<?php
namespace App\Models;

use DB\SQL\Mapper;
use App\Security\Security;

Security::check();

class Pessoa extends Mapper
{
    public function __construct(\DB\SQL $db)
    {
        parent::__construct($db, 'pessoa'); // tabela: pessoa
    }

    /**
     * Preenche atributos permitidos a partir de um array (request body).
     */
    public function setFromArray(array $data): void
    {
        $fillable = [
            'nome',
            'sobrenome',
            'sexo',                   // 'M','F','OUTRO'
            'situacao',               // 'ativo','inativo'
            'data_nascimento',
            'nacionalidade',
            'cidade_nascimento_id',   // FK cidade
            'estado_id',              // FK estado
            'cpf',
            'numero_carteira_ieclb',
            'estado_civil',
            'regime_casamento',       // 'separacao_bens','comunhao_parcial','comunhao_universal'
            'nome_pai',
            'nome_mae',
        ];

        foreach ($fillable as $field) {
            if (array_key_exists($field, $data)) {
                if ($field === 'cpf') {
                    $this->setCPF($data[$field]);
                } else {
                    $this->$field = $data[$field];
                }
            }
        }
    }

    /**
     * Normaliza e define CPF (somente dígitos, 11 chars ou null).
     */
    public function setCPF(?string $cpf): void
    {
        if ($cpf === null || $cpf === '') {
            $this->cpf = null;
            return;
        }
        $digits = preg_replace('/\D+/', '', $cpf);
        $this->cpf = $digits ?: null;
    }

    /**
     * Retorna payload serializável.
     */
    public function toArray(): array
    {
        return [
            'id'                     => $this->id !== null ? (int)$this->id : null,
            'nome'                   => $this->nome,
            'sobrenome'              => $this->sobrenome,
            'sexo'                   => $this->sexo,
            'situacao'               => $this->situacao,
            'data_nascimento'        => $this->data_nascimento,
            'nacionalidade'          => $this->nacionalidade,
            'cidade_nascimento_id'   => $this->cidade_nascimento_id,
            'estado_id'              => $this->estado_id,
            'cpf'                    => $this->cpf,
            'numero_carteira_ieclb'  => $this->numero_carteira_ieclb,
            'estado_civil'           => $this->estado_civil,
            'regime_casamento'       => $this->regime_casamento,
            'nome_pai'               => $this->nome_pai,
            'nome_mae'               => $this->nome_mae,
            'created_at'             => $this->created_at ?? null,
            'updated_at'             => $this->updated_at ?? null,
        ];
    }

    /**
     * Idade calculada (anos inteiros) ou null se sem data.
     */
    public function idade(): ?int
    {
        if (empty($this->data_nascimento)) {
            return null;
        }
        try {
            $dob = new \DateTime($this->data_nascimento);
            $now = new \DateTime('today');
            return (int)$dob->diff($now)->y;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
