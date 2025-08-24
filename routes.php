<?php

if (!defined('APP_INIT')) {
    header('Location: /');
    exit;
}

$agora = new DateTime();

// Subtrai 10 minutos para enrrolar noobs
$agora->sub(new DateInterval('PT4H'));

$f3->route('GET /', function() use ($agora) {
    echo json_encode([
        'status' => 'ok',
        'message' => 'API atualizada em ' . $agora->format('Y-m-d H:i:s')
    ]);
});


// Auth
$f3->route('POST /auth/login', 'App\\Controllers\\Auth\\AuthController->login');
$f3->route('POST /auth/refresh', 'App\\Controllers\\Auth\\AuthController->refresh');
$f3->route('POST /auth/logout', 'App\\Controllers\\Auth\\AuthController->logout');

// ====================== USERS ======================
$f3->route('GET /users', 'App\\Controllers\\Auth\\UserController->index');
$f3->route('GET /users/@id', 'App\\Controllers\\Auth\\UserController->show');
$f3->route('POST /users', 'App\\Controllers\\Auth\\UserController->create');
$f3->route('PUT /users/@id', 'App\\Controllers\\Auth\\UserController->update');
$f3->route('DELETE /users/@id', 'App\\Controllers\\Auth\\UserController->delete');

// ====================== ROLES ======================
$f3->route('GET /roles', 'App\\Controllers\\Auth\\RoleController->index');
$f3->route('GET /roles/@id', 'App\\Controllers\\Auth\\RoleController->show');
$f3->route('POST /roles', 'App\\Controllers\\Auth\\RoleController->create');
$f3->route('PUT /roles/@id', 'App\\Controllers\\Auth\\RoleController->update');
$f3->route('DELETE /roles/@id', 'App\\Controllers\\Auth\\RoleController->delete');

// ====================== PERMISSIONS ======================
$f3->route('GET /permissions', 'App\\Controllers\\Auth\\PermissionController->index');
$f3->route('GET /permissions/@id', 'App\\Controllers\\Auth\\PermissionController->show');
$f3->route('POST /permissions', 'App\\Controllers\\Auth\\PermissionController->create');
$f3->route('PUT /permissions/@id', 'App\\Controllers\\Auth\\PermissionController->update');
$f3->route('DELETE /permissions/@id', 'App\\Controllers\\Auth\\PermissionController->delete');

// ====================== ROLE_PERMISSIONS ======================
$f3->route('GET /role-permissions', 'App\\Controllers\\Auth\\RolePermissionController->index');
$f3->route('GET /role-permissions/@id', 'App\\Controllers\\Auth\\RolePermissionController->show');
$f3->route('POST /role-permissions', 'App\\Controllers\\Auth\\RolePermissionController->create');
$f3->route('PUT /role-permissions/@id', 'App\\Controllers\\Auth\\RolePermissionController->update');
$f3->route('DELETE /role-permissions/@id', 'App\\Controllers\\Auth\\RolePermissionController->delete');

// ====================== USER_ROLES ======================
$f3->route('GET /user-roles', 'App\\Controllers\\Auth\\UserRoleController->index');
$f3->route('GET /user-roles/@id', 'App\\Controllers\\Auth\\UserRoleController->show');
$f3->route('POST /user-roles', 'App\\Controllers\\Auth\\UserRoleController->create');
$f3->route('PUT /user-roles/@id', 'App\\Controllers\\Auth\\UserRoleController->update');
$f3->route('DELETE /user-roles/@id', 'App\\Controllers\\Auth\\UserRoleController->delete');

// ====================== REFRESH TOKENS ======================
$f3->route('GET /refresh-tokens', 'App\\Controllers\\Auth\\RefreshTokenController->index');
$f3->route('GET /refresh-tokens/@id', 'App\\Controllers\\Auth\\RefreshTokenController->show');
$f3->route('POST /refresh-tokens', 'App\\Controllers\\Auth\\RefreshTokenController->create');
$f3->route('PUT /refresh-tokens/@id', 'App\\Controllers\\Auth\\RefreshTokenController->update');
$f3->route('DELETE /refresh-tokens/@id', 'App\\Controllers\\Auth\\RefreshTokenController->delete');

// ====================== ESTADO ======================
$f3->route('GET /estados', 'App\\Controllers\\EstadoController->index');
$f3->route('GET /estados/@id', 'App\\Controllers\\EstadoController->show');
$f3->route('POST /estados', 'App\\Controllers\\EstadoController->create');
$f3->route('PUT /estados/@id', 'App\\Controllers\\EstadoController->update');
$f3->route('DELETE /estados/@id', 'App\\Controllers\\EstadoController->delete');

// ====================== CIDADE ======================
$f3->route('GET /cidades', 'App\\Controllers\\CidadeController->index');
$f3->route('GET /cidades/@id', 'App\\Controllers\\CidadeController->show');
$f3->route('POST /cidades', 'App\\Controllers\\CidadeController->create');
$f3->route('PUT /cidades/@id', 'App\\Controllers\\CidadeController->update');
$f3->route('DELETE /cidades/@id', 'App\\Controllers\\CidadeController->delete');

// ====================== PESSOA ======================
$f3->route('GET /pessoas', 'App\\Controllers\\PessoaController->index');
$f3->route('GET /pessoas/@id', 'App\\Controllers\\PessoaController->show');
$f3->route('POST /pessoas', 'App\\Controllers\\PessoaController->create');
$f3->route('PUT /pessoas/@id', 'App\\Controllers\\PessoaController->update');
$f3->route('DELETE /pessoas/@id', 'App\\Controllers\\PessoaController->delete');

// ====================== ENDERECO ======================
$f3->route('GET /enderecos', 'App\\Controllers\\EnderecoController->index');
$f3->route('GET /enderecos/@id', 'App\\Controllers\\EnderecoController->show');
$f3->route('POST /enderecos', 'App\\Controllers\\EnderecoController->create');
$f3->route('PUT /enderecos/@id', 'App\\Controllers\\EnderecoController->update');
$f3->route('DELETE /enderecos/@id', 'App\\Controllers\\EnderecoController->delete');

// ====================== CONTATO ======================
$f3->route('GET /contatos', 'App\\Controllers\\ContatoController->index');
$f3->route('GET /contatos/@id', 'App\\Controllers\\ContatoController->show');
$f3->route('POST /contatos', 'App\\Controllers\\ContatoController->create');
$f3->route('PUT /contatos/@id', 'App\\Controllers\\ContatoController->update');
$f3->route('DELETE /contatos/@id', 'App\\Controllers\\ContatoController->delete');

// ====================== PARENTESCOS ======================
$f3->route('GET /parentescos', 'App\\Controllers\\ParentescosController->index');
$f3->route('GET /parentescos/@id', 'App\\Controllers\\ParentescosController->show');
$f3->route('POST /parentescos', 'App\\Controllers\\ParentescosController->create');
$f3->route('PUT /parentescos/@id', 'App\\Controllers\\ParentescosController->update');
$f3->route('DELETE /parentescos/@id', 'App\\Controllers\\ParentescosController->delete');

// ====================== INTEGRACAO_DESLIGAMENTO ======================
$f3->route('GET /integracoes-desligamentos', 'App\\Controllers\\IntegracaoDesligamentoController->index');
$f3->route('GET /integracoes-desligamentos/@id', 'App\\Controllers\\IntegracaoDesligamentoController->show');
$f3->route('POST /integracoes-desligamentos', 'App\\Controllers\\IntegracaoDesligamentoController->create');
$f3->route('PUT /integracoes-desligamentos/@id', 'App\\Controllers\\IntegracaoDesligamentoController->update');
$f3->route('DELETE /integracoes-desligamentos/@id', 'App\\Controllers\\IntegracaoDesligamentoController->delete');

// ====================== DADOS_INTEGRACAO ======================
$f3->route('GET /dados-integracao', 'App\\Controllers\\DadosIntegracaoController->index');
$f3->route('GET /dados-integracao/@id', 'App\\Controllers\\DadosIntegracaoController->show');
$f3->route('POST /dados-integracao', 'App\\Controllers\\DadosIntegracaoController->create');
$f3->route('PUT /dados-integracao/@id', 'App\\Controllers\\DadosIntegracaoController->update');
$f3->route('DELETE /dados-integracao/@id', 'App\\Controllers\\DadosIntegracaoController->delete');

// ====================== INSTRUCAO ======================
$f3->route('GET /instrucao', 'App\\Controllers\\InstrucaoController->index');
$f3->route('GET /instrucao/@id', 'App\\Controllers\\InstrucaoController->show');
$f3->route('POST /instrucao', 'App\\Controllers\\InstrucaoController->create');
$f3->route('PUT /instrucao/@id', 'App\\Controllers\\InstrucaoController->update');
$f3->route('DELETE /instrucao/@id', 'App\\Controllers\\InstrucaoController->delete');

// ====================== DADOS_PROFISSIONAIS ======================
$f3->route('GET /dados-profissionais', 'App\\Controllers\\DadosProfissionaisController->index');
$f3->route('GET /dados-profissionais/@id', 'App\\Controllers\\DadosProfissionaisController->show');
$f3->route('POST /dados-profissionais', 'App\\Controllers\\DadosProfissionaisController->create');
$f3->route('PUT /dados-profissionais/@id', 'App\\Controllers\\DadosProfissionaisController->update');
$f3->route('DELETE /dados-profissionais/@id', 'App\\Controllers\\DadosProfissionaisController->delete');

// ====================== INFORMACOES_COMPLEMENTARES ======================
$f3->route('GET /informacoes-complementares', 'App\\Controllers\\InformacoesComplementaresController->index');
$f3->route('GET /informacoes-complementares/@id', 'App\\Controllers\\InformacoesComplementaresController->show');
$f3->route('POST /informacoes-complementares', 'App\\Controllers\\InformacoesComplementaresController->create');
$f3->route('PUT /informacoes-complementares/@id', 'App\\Controllers\\InformacoesComplementaresController->update');
$f3->route('DELETE /informacoes-complementares/@id', 'App\\Controllers\\InformacoesComplementaresController->delete');