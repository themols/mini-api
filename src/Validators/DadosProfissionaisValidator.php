<?php
namespace App\Validators;

use App\Security\Security;

Security::check();


class DadosProfissionaisValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if (empty($data['pessoa_id']) || !is_numeric($data['pessoa_id'])) {
            $errors['pessoa_id'] = 'Pessoa é obrigatória e deve ser um ID numérico.';
        }

        if (empty($data['nome_empresa'])) {
            $errors['nome_empresa'] = 'Nome da empresa é obrigatório.';
        }

        if (empty($data['cargo'])) {
            $errors['cargo'] = 'Cargo é obrigatório.';
        }

        if (empty($data['profissao'])) {
            $errors['profissao'] = 'Profissão é obrigatória.';
        }

        if (isset($data['aposentado']) && !is_bool($data['aposentado'])) {
            $errors['aposentado'] = 'Campo aposentado deve ser booleano.';
        }

        if (!empty($data['aposentado']) && empty($data['data_aposentadoria'])) {
            $errors['data_aposentadoria'] = 'Data de aposentadoria é obrigatória se aposentado for true.';
        }

        return $errors;
    }
}
