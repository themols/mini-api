<?php
namespace App\Validators;

class InstrucaoValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if (empty($data['pessoa_id']) || !is_numeric($data['pessoa_id'])) {
            $errors['pessoa_id'] = 'Pessoa é obrigatória e deve ser um ID numérico.';
        }

        $allowedGrau = ['analfabeto', 'ensino_fundamental', 'ensino_medio', 'ensino_superior', 'pos_graduacao', 'mestrado', 'doutorado', 'pos_doutorado'];
        if (empty($data['grau_estudo']) || !in_array($data['grau_estudo'], $allowedGrau)) {
            $errors['grau_estudo'] = 'Grau de estudo inválido.';
        }

        $allowedSituacao = ['cursando', 'completo', 'incompleto'];
        if (empty($data['situacao']) || !in_array($data['situacao'], $allowedSituacao)) {
            $errors['situacao'] = 'Situação inválida.';
        }

        if (empty($data['entidade_escolar'])) {
            $errors['entidade_escolar'] = 'Entidade escolar é obrigatória.';
        }

        return $errors;
    }
}
