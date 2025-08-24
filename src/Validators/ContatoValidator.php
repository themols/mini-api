<?php
namespace App\Validators;

class ContatoValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if (empty($data['pessoa_id']) || !is_numeric($data['pessoa_id'])) {
            $errors['pessoa_id'] = 'Pessoa_id é obrigatório e deve ser numérico.';
        }

        if (empty($data['telefone_residencial']) && empty($data['telefone_celular']) && empty($data['email'])) {
            $errors['contato'] = 'Pelo menos um contato deve ser informado (telefone ou email).';
        }

        if (!isset($data['dados_proprios'])) {
            $errors['dados_proprios'] = 'O campo dados_proprios deve ser informado (true/false).';
        }

        if (isset($data['dados_proprios']) && $data['dados_proprios'] === false && empty($data['responsavel'])) {
            $errors['responsavel'] = 'Responsável deve ser informado se dados_proprios for false.';
        }

        return $errors;
    }
}
