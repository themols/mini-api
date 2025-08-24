<?php
namespace App\Validators;

use App\Security\Security;

Security::check();


class CidadeValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty($data['nome'])) {
            $errors[] = "O campo 'nome' é obrigatório.";
        }

        if (empty($data['estado_id']) || !is_numeric($data['estado_id'])) {
            $errors[] = "O campo 'estado_id' é obrigatório e deve ser numérico.";
        }

        return $errors;
    }
}
