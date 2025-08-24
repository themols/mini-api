<?php
namespace App\Models;

use DB\SQL\Mapper;
use App\Security\Security;

Security::check();

class Estado extends Mapper
{
    public function __construct($db)
    {
        parent::__construct($db, 'estado');
    }

    public function toArray(): array
    {
        return $this->cast();
    }
}
