<?php
class InformacoesComplementaresValidator {

    public static function validate($data) {
        $errors = [];

        if (!isset($data['pessoa_id']) || !is_numeric($data['pessoa_id'])) {
            $errors['pessoa_id'] = 'Pessoa ID é obrigatório e deve ser um número.';
        }

        if (isset($data['contribuicao_mensal']) && !is_numeric($data['contribuicao_mensal'])) {
            $errors['contribuicao_mensal'] = 'Contribuição mensal deve ser um número válido.';
        }

        if (isset($data['grupo_setor_atividade']) && strlen($data['grupo_setor_atividade']) > 255) {
            $errors['grupo_setor_atividade'] = 'O grupo/setor de atividade deve ter no máximo 255 caracteres.';
        }

        return $errors;
    }
}
