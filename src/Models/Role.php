<?php
namespace App\Models;

use DB\SQL\Mapper;
use DB\SQL;

use App\Security\Security;

Security::check();

class Role extends Mapper
{
    public function __construct(SQL $db)
    {
        parent::__construct($db, 'roles');
    }

    /**
     * Get permissions associated to role
     * @return array
     */
    public function getPermissions(): array
    {
        $db = \Base::instance()->get('DB');
        return $db->exec('SELECT p.* FROM permissions p JOIN role_permissions rp ON p.id=rp.permission_id WHERE rp.role_id=?', [(int)$this->id]);
    }

    /**
     * Set permissions for this role (replace existing)
     * @param array $permissionIds
     * @return void
     */
    public function setPermissions(array $permissionIds): void
    {
        $db = \Base::instance()->get('DB');
        $db->exec('DELETE FROM role_permissions WHERE role_id=?', [(int)$this->id]);
        foreach ($permissionIds as $pid) {
            $db->exec('INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?, ?)', [(int)$this->id, (int)$pid]);
        }
    }
}
