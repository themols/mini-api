<?php
namespace App\Services;

use App\Models\User;

class UserService
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllUsers(): array
    {
        $userModel = new User($this->db);
        $rows = $userModel->find();
        $out = [];

        foreach ($rows as $row) {
            $u = new User($this->db);
            $u->load(['id=?', $row->id]);
          
            $roles = array_map(fn($r) => $r['name'], $u->getRoles());
            $perms = array_map(fn($p) => $p['name'], $u->getPermissions());
        
            $out[] = [
                'id' => (int)$u->id,
                'username' => $u->username,
                'email' => $u->email,
                'roles' => $roles,
                'permissions' => $perms
            ];
        }

        return $out;
    }

    public function getUserById(int $id): ?array
    {
        $u = new User($this->db);
        $u->load(['id=?', $id]);
       
        if ($u->dry()) return null;

        $roles = array_map(fn($r) => $r['name'], $u->getRoles());
        $perms = array_map(fn($p) => $p['name'], $u->getPermissions());

        return [
            'id' => (int)$u->id,
            'username' => $u->username,
            'email' => $u->email,
            'roles' => $roles,
            'permissions' => $perms
        ];
    }

    public function createUser(array $data): int
    {
        $u = new User($this->db);
      
        $u->username = $data['username'] ?? 'user_'.bin2hex(random_bytes(3));
        $u->email = $data['email'] ?? null;
        $u->setPassword($data['password'] ?? bin2hex(random_bytes(5)));
        $u->save();

        if (!empty($data['roles']) && is_array($data['roles'])) {
            $u->setRoles($data['roles']);
        }

        return (int)$u->id;
    }

    public function updateUser(int $id, array $data): ?int
    {
        $u = new User($this->db);
        $u->load(['id=?', $id]);
        if ($u->dry()) return null;

        if (isset($data['username'])) $u->username = $data['username'];
        if (isset($data['email'])) $u->email = $data['email'];
        if (!empty($data['password'])) $u->setPassword($data['password']);

        $u->save();

        if (isset($data['roles']) && is_array($data['roles'])) {
            $u->setRoles($data['roles']);
        }

        return (int)$u->id;
    }

    public function deleteUser(int $id): bool
    {
        $u = new User($this->db);
        $u->load(['id=?', $id]);
     
        if ($u->dry()) return false;

        $u->erase();
        
        return true;
    }
}
