<?php
namespace App\Validators;

class EnderecoValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if (empty($data['pessoa_id']) || !is_numeric($data['pessoa_id'])) {
            $errors['pessoa_id'] = 'Pessoa_id é obrigatório e deve ser numérico.';
        }

        if (empty($data['cep'])) {
            $errors['cep'] = 'CEP é obrigatório.';
        }

        if (empty($data['estado_id']) || !is_numeric($data['estado_id'])) {
            $errors['estado_id'] = 'Estado_id é obrigatório e deve ser numérico.';
        }

        if (empty($data['cidade_id']) || !is_numeric($data['cidade_id'])) {
            $errors['cidade_id'] = 'Cidade_id é obrigatório e deve ser numérico.';
        }

        if (empty($data['logradouro'])) {
            $errors['logradouro'] = 'Logradouro é obrigatório.';
        }

        if (empty($data['numero'])) {
            $errors['numero'] = 'Número é obrigatório.';
        }

        if (!isset($data['endereco_principal'])) {
            $errors['endereco_principal'] = 'Endereço_principal deve ser informado (true/false).';
        }

        if (!isset($data['endereco_correspondencia'])) {
            $errors['endereco_correspondencia'] = 'Endereco_correspondencia deve ser informado (sim/não).';
        }

        return $errors;
    }
}
