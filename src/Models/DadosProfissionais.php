<?php
namespace App\Models;

use DB\SQL\Mapper;

class DadosProfissionais extends Mapper
{
    public function __construct(\DB\SQL $db)
    {
        parent::__construct($db, 'dados_profissionais');
    }
}
