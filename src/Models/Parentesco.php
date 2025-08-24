<?php
namespace App\Models;

use DB\SQL\Mapper;

class Parentesco extends Mapper
{
    public function __construct(\DB\SQL $db)
    {
        parent::__construct($db, 'parentescos');
    }
}
