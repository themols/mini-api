<?php

$f3->route('GET /', function() {
    echo json_encode([
        'status' => 'ok',
        'message' => 'API rodando com sucesso!'
    ]);
});

// Auth
$f3->route('POST /auth/login', 'App\\Controllers\\AuthController->login');
$f3->route('POST /auth/refresh', 'App\\Controllers\\AuthController->refresh');
$f3->route('POST /auth/logout', 'App\\Controllers\\AuthController->logout');

// Users
$f3->route('GET /users', 'App\\Controllers\\UserController->index');
$f3->route('GET /users/@id', 'App\\Controllers\\UserController->show');
$f3->route('POST /users', 'App\\Controllers\\UserController->create');
$f3->route('PUT /users/@id', 'App\\Controllers\\UserController->update');
$f3->route('DELETE /users/@id', 'App\\Controllers\\UserController->delete');

// Roles & Permissions
$f3->route('GET /roles', 'App\\Controllers\\RoleController->index');
$f3->route('GET /roles/@id', 'App\\Controllers\\RoleController->show');
$f3->route('POST /roles', 'App\\Controllers\\RoleController->create');
$f3->route('PUT /roles/@id', 'App\\Controllers\\RoleController->update');
$f3->route('DELETE /roles/@id', 'App\\Controllers\\RoleController->delete');

$f3->route('GET /permissions', 'App\\Controllers\\PermissionController->index');
$f3->route('GET /permissions/@id', 'App\\Controllers\\PermissionController->show');
$f3->route('POST /permissions', 'App\\Controllers\\PermissionController->create');
$f3->route('PUT /permissions/@id', 'App\\Controllers\\PermissionController->update');
$f3->route('DELETE /permissions/@id', 'App\\Controllers\\PermissionController->delete');

// User Roles (assignments)
$f3->route('GET /user-roles', 'App\\Controllers\\UserRoleController->index');
$f3->route('GET /user-roles/user/@user_id', 'App\\Controllers\\UserRoleController->listByUser');
$f3->route('POST /user-roles', 'App\\Controllers\\UserRoleController->assign');
$f3->route('DELETE /user-roles/user/@user_id/role/@role_id', 'App\\Controllers\\UserRoleController->remove');
