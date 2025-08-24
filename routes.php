<?php

$f3->route('GET /', function() {
    echo json_encode([
        'status' => 'ok',
        'message' => 'API rodando com sucesso!'
    ]);
});

// Auth
$f3->route('POST /auth/login', 'App\\Controllers\\Auth\\AuthController->login');
$f3->route('POST /auth/refresh', 'App\\Controllers\\Auth\\AuthController->refresh');
$f3->route('POST /auth/logout', 'App\\Controllers\\Auth\\AuthController->logout');

// Users
$f3->route('GET /users', 'App\\Controllers\\Auth\\UserController->index');
$f3->route('GET /users/@id', 'App\\Controllers\\Auth\\UserController->show');
$f3->route('POST /users', 'App\\Controllers\\Auth\\UserController->create');
$f3->route('PUT /users/@id', 'App\\Controllers\\Auth\\UserController->update');
$f3->route('DELETE /users/@id', 'App\\Controllers\\Auth\\UserController->delete');

// Roles & Permissions
$f3->route('GET /roles', 'App\\Controllers\\Auth\\RoleController->index');
$f3->route('GET /roles/@id', 'App\\Controllers\\Auth\\RoleController->show');
$f3->route('POST /roles', 'App\\Controllers\\Auth\\RoleController->create');
$f3->route('PUT /roles/@id', 'App\\Controllers\\Auth\\RoleController->update');
$f3->route('DELETE /roles/@id', 'App\\Controllers\\Auth\\RoleController->delete');

$f3->route('GET /permissions', 'App\\Controllers\\Auth\\PermissionController->index');
$f3->route('GET /permissions/@id', 'App\\Controllers\\Auth\\PermissionController->show');
$f3->route('POST /permissions', 'App\\Controllers\\Auth\\PermissionController->create');
$f3->route('PUT /permissions/@id', 'App\\Controllers\\Auth\\PermissionController->update');
$f3->route('DELETE /permissions/@id', 'App\\Controllers\\Auth\\PermissionController->delete');

// User Roles (assignments)
$f3->route('GET /user-roles', 'App\\Controllers\\Auth\\UserRoleController->index');
$f3->route('GET /user-roles/user/@user_id', 'App\\Controllers\\Auth\\UserRoleController->listByUser');
$f3->route('POST /user-roles', 'App\\Controllers\\Auth\\UserRoleController->assign');
$f3->route('DELETE /user-roles/user/@user_id/role/@role_id', 'App\\Controllers\\Auth\\UserRoleController->remove');
