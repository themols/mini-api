<?php
namespace App\Validators;

use App\Security\Security;

Security::check();


class IntegracaoDesligamentoValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if (empty($data['pessoa']) || !is_numeric($data['pessoa'])) {
            $errors['pessoa'] = 'Pessoa é obrigatória e deve ser um ID numérico.';
        }

        if (empty($data['forma_ingresso'])) {
            $errors['forma_ingresso'] = 'Forma de ingresso é obrigatória.';
        }

        if ($data['forma_ingresso'] === 'outra' && empty($data['outro'])) {
            $errors['outro'] = 'Campo "outro" é obrigatório quando forma_ingresso é "outra".';
        }

        if (empty($data['data_ingresso_ieclb'])) {
            $errors['data_ingresso_ieclb'] = 'Data de ingresso na IECLB é obrigatória.';
        }

        if (empty($data['data_admissao_comunidade'])) {
            $errors['data_admissao_comunidade'] = 'Data de admissão na comunidade é obrigatória.';
        }

        if (!empty($data['cidade']) && !is_numeric($data['cidade'])) {
            $errors['cidade'] = 'Cidade deve ser um ID numérico.';
        }

        if (!empty($data['estado']) && !is_numeric($data['estado'])) {
            $errors['estado'] = 'Estado deve ser um ID numérico.';
        }

        return $errors;
    }
}
