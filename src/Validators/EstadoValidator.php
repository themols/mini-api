<?php
namespace App\Validators;

use App\Security\Security;

Security::check();


class EstadoValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        // Nome é obrigatório
        if (empty($data['nome'])) {
            $errors[] = "O campo 'nome' é obrigatório.";
        } elseif (strlen($data['nome']) < 2) {
            $errors[] = "O campo 'nome' deve ter pelo menos 2 caracteres.";
        }

        return $errors;
    }
}
