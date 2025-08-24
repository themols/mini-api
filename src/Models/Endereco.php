<?php
namespace App\Models;

use DB\SQL\Mapper;

class Endereco extends Mapper
{
    public function __construct(\DB\SQL $db)
    {
        parent::__construct($db, 'endereco');
    }
}
