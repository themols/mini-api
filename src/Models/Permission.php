<?php
namespace App\Models;

use DB\SQL\Mapper;
use DB\SQL;

/**
 * Class Permission
 * @package App\Models
 */
class Permission extends Mapper
{
    public function __construct(SQL $db)
    {
        parent::__construct($db, 'permissions');
    }
}
