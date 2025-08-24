<?php
namespace App\Validators;

use App\Security\Security;

Security::check();


class PessoaValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty($data['nome'])) {
            $errors[] = "O campo 'nome' é obrigatório.";
        }

        if (empty($data['sobrenome'])) {
            $errors[] = "O campo 'sobrenome' é obrigatório.";
        }

        if (empty($data['sexo']) || !in_array($data['sexo'], ['M','F','OUTRO'])) {
            $errors[] = "O campo 'sexo' é inválido. Valores aceitos: M, F, OUTRO.";
        }

        if (!empty($data['cpf']) && !preg_match('/^\d{11}$/', $data['cpf'])) {
            $errors[] = "O CPF deve conter apenas 11 dígitos.";
        }

        return $errors;
    }
}
