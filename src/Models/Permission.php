<?php
namespace App\Models;

use DB\SQL\Mapper;
use DB\SQL;
use App\Security\Security;

Security::check();

class Permission extends Mapper
{
    public function __construct(SQL $db)
    {
        parent::__construct($db, 'permissions');
    }
}
