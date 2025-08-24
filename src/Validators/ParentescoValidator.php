<?php
namespace App\Validators;

class ParentescoValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if (empty($data['tipo_parentesco'])) {
            $errors['tipo_parentesco'] = 'O tipo de parentesco é obrigatório.';
        }

        if (empty($data['pessoa']) || !is_numeric($data['pessoa'])) {
            $errors['pessoa'] = 'Pessoa é obrigatória e deve ser um ID numérico.';
        }

        if (empty($data['parente']) || !is_numeric($data['parente'])) {
            $errors['parente'] = 'Parente é obrigatório e deve ser um ID numérico.';
        }

        return $errors;
    }
}
