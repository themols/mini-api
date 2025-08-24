<?php
namespace App\Models;

use DB\SQL\Mapper;
use DB\SQL;

use App\Security\Security;

Security::check();

class User extends Mapper
{
    /**
     * User constructor.
     *
     * @param SQL $db
     */
    public function __construct(SQL $db)
    {
        parent::__construct($db, 'users');
    }

    /**
     * Define a senha do usuário (hash).
     *
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verifica se a senha informada corresponde ao hash.
     *
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password ?? '');
    }

    /**
     * Retorna roles do usuário
     *
     * @return array
     */
    public function getRoles(): array
    {
        $db = \Base::instance()->get('DB');
        return $db->exec('SELECT r.* FROM roles r JOIN user_roles ur ON r.id=ur.role_id WHERE ur.user_id=?', [(int)$this->id]);
    }

    /**
     * Define roles do usuário (substitui existentes).
     *
     * @param array $roleIds
     * @return void
     */
    public function setRoles(array $roleIds): void
    {
        $db = \Base::instance()->get('DB');
        $db->exec('DELETE FROM user_roles WHERE user_id=?', [(int)$this->id]);
        foreach ($roleIds as $rid) {
            $db->exec('INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)', [(int)$this->id, (int)$rid]);
        }
    }

    /**
     * Adiciona uma role ao usuário.
     *
     * @param int $roleId
     * @return void
     */
    public function addRole(int $roleId): void
    {
        $db = \Base::instance()->get('DB');
        $db->exec('INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)', [(int)$this->id, $roleId]);
    }

    /**
     * Remove uma role do usuário.
     *
     * @param int $roleId
     * @return void
     */
    public function removeRole(int $roleId): void
    {
        $db = \Base::instance()->get('DB');
        $db->exec('DELETE FROM user_roles WHERE user_id=? AND role_id=?', [(int)$this->id, (int)$roleId]);
    }

    /**
     * Retorna permissões agregadas pelas roles do usuário.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        $db = \Base::instance()->get('DB');
        return $db->exec('SELECT DISTINCT p.* FROM permissions p JOIN role_permissions rp ON p.id=rp.permission_id JOIN user_roles ur ON rp.role_id=ur.role_id WHERE ur.user_id=?', [(int)$this->id]);
    }
}
