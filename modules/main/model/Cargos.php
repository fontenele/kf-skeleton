<?php

namespace Main\Model;

class Cargos extends \Kf\Module\Model {

    public function __construct() {
        $this->_table = 'public.cargo';
        $this->_sequence = 'public.cargo_cod_seq';
        $this->_pk = 'cod';

        $this->addField('cod', self::TYPE_INTEGER);
        $this->addField('name', self::TYPE_VARCHAR, 100);
        $this->addField('salario', self::TYPE_MONEY);
    }

}
