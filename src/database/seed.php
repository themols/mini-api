<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Carrega configuração
$config = require __DIR__ . '/../config/config.php';

use DB\SQL;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

// Conecta ao banco
$db = new SQL(
    $config['db']['dsn'],
    $config['db']['username'],
    $config['db']['password']
);

// --- Criar Roles ---
$roles = ['USER', 'ADMIN', 'SUPER_ADMIN'];
foreach ($roles as $roleName) {
    $role = new Role($db);
    $role->load(['name=?', $roleName]);
    if ($role->dry()) {
        $role->name = $roleName;
        $role->save();
        echo "Role '{$roleName}' criada.\n";
    } else {
        echo "Role '{$roleName}' já existe.\n";
    }
}

// --- Criar Permissions (CRUD) ---
$controllers = ['users', 'roles', 'permissions', 'user_roles'];
$actions = ['create', 'read', 'update', 'delete'];

foreach ($controllers as $ctrl) {
    foreach ($actions as $action) {
        $permName = "{$ctrl}.{$action}";
        $perm = new Permission($db);
        $perm->load(['name=?', $permName]);
        if ($perm->dry()) {
            $perm->name = $permName;
            $perm->save();
            echo "Permission '{$permName}' criada.\n";
        } else {
            echo "Permission '{$permName}' já existe.\n";
        }
    }
}

// --- Associar Permissions às Roles ---
foreach ($roles as $roleName) {
    $role = new Role($db);
    $role->load(['name=?', $roleName]);
    if ($role->dry()) {
        echo "Erro: Role '{$roleName}' não encontrada. Pulando associação.\n";
        continue;
    }

    foreach ($controllers as $ctrl) {
        foreach ($actions as $action) {
            $permName = "{$ctrl}.{$action}";
            $perm = new Permission($db);
            $perm->load(['name=?', $permName]);
            if ($perm->dry()) {
                echo "Erro: Permission '{$permName}' não encontrada. Pulando.\n";
                continue;
            }

            // Verifica se já existe
            $check = $db->exec(
                "SELECT id FROM role_permissions WHERE role_id=? AND permission_id=?",
                [$role->id, $perm->id]
            );
            if (!$check) {
                $db->exec(
                    "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)",
                    [$role->id, $perm->id]
                );
                echo "Associado Role '{$roleName}' → Permission '{$permName}'.\n";
            }
        }
    }
}

// --- Criar usuário administrador ---
$admin = new User($db);
$admin->load(['email=?', 'contato@ems.dev']);
if ($admin->dry()) {
    $admin->username = 'Administrador';
    $admin->email = 'contato@ems.dev';
    $admin->password = password_hash('admin', PASSWORD_BCRYPT);
    $admin->save();
    echo "Usuário 'administrador' criado.\n";
}

// Carrega novamente para garantir que $admin->id exista
$admin->load(['email=?', 'contato@ems.dev']);
if ($admin->dry()) {
    echo "Erro: Falha ao criar ou carregar usuário 'administrador'. Abortando.\n";
    exit;
}

// --- Associar SUPER_ADMIN ao usuário administrador ---
$superAdminRole = new Role($db);
$superAdminRole->load(['name=?', 'SUPER_ADMIN']);
if ($superAdminRole->dry()) {
    echo "Erro: Role 'SUPER_ADMIN' não encontrada. Abortando.\n";
    exit;
}

// Verifica se já existe a associação
$check = $db->exec(
    "SELECT id FROM user_roles WHERE user_id=? AND role_id=?",
    [$admin->id, $superAdminRole->id]
);
if (!$check) {
    $db->exec(
        "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)",
        [$admin->id, $superAdminRole->id]
    );
    echo "Associada Role 'SUPER_ADMIN' ao usuário 'administrador'.\n";
} else {
    echo "Usuário 'administrador' já possui a Role 'SUPER_ADMIN'.\n";
}

echo "✅ Seed executado com sucesso!\n";
