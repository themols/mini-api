<?php
use \DB\SQL\Mapper;
class InformacoesComplementares extends Mapper {
    public function __construct(\DB\SQL $db) {
        parent::__construct($db, 'informacoes_complementares');
    }
}
