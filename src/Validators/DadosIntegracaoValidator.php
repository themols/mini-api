<?php
namespace App\Validators;

class DadosIntegracaoValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if (empty($data['integracao_desligamento_id']) || !is_numeric($data['integracao_desligamento_id'])) {
            $errors['integracao_desligamento_id'] = 'Integracao/Desligamento é obrigatória e deve ser um ID numérico.';
        }

        if (empty($data['enum_integracao'])) {
            $errors['enum_integracao'] = 'Tipo de integração é obrigatório.';
        } else {
            $allowed = ['BATISMO', 'CONFIRMACAO', 'CASAMENTO_CIVIL', 'CASAMENTO_RELIGIOSO'];
            if (!in_array($data['enum_integracao'], $allowed)) {
                $errors['enum_integracao'] = 'Tipo de integração inválido.';
            }
        }

        if (empty($data['data'])) {
            $errors['data'] = 'Data é obrigatória.';
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
