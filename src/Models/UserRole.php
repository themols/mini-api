<?php
namespace App\Models;

use DB\SQL\Mapper;
use DB\SQL;

use App\Security\Security;

Security::check();

class UserRole extends Mapper
{
    public function __construct(SQL $db)
    {
        parent::__construct($db, 'user_roles');
    }
}
