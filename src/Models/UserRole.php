<?php
namespace App\Models;

use DB\SQL\Mapper;
use DB\SQL;

/**
 * Class UserRole
 * @package App\Models
 */
class UserRole extends Mapper
{
    public function __construct(SQL $db)
    {
        parent::__construct($db, 'user_roles');
    }
}
