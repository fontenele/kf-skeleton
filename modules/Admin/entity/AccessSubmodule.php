<?php

namespace Admin\Entity;

class AccessSubmodule extends \Kf\Module\Entity {

    public function configure($recursive) {
        $this->setTable('public.access_submodule'); // Table
        $this->setSequence('public.access_submodule_cod_seq'); // Sequence
        $this->setPrimaryKey('cod'); // Primary Key
        $this->setServiceName('\Admin\Service\AccessSubmodule'); // Service Name
        // Cod
        $this->addField(self::createField('cod')
                        ->setDbName('cod')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_INTEGER)
                        ->setViewComponent(\Kf\View\Html\InputHidden::create('cod')));
        // Name
        $this->addField(self::createField('name')
                        ->setDbName('name')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(200)
                        ->setDbOrderBySequence(2)
                        ->setDbOrderBySortType('ASC')
                        ->setSearchCriteria(\Kf\Database\Criteria::create(\Kf\Database\Criteria::CONDITION_LIKE))
                        ->setDatagridHeader(\Kf\View\Html\Datagrid\Header::create(2, 'SubMódulo', '60%'))
                        ->setViewComponent(\Kf\View\Html\InputText::create('name', 'SubMódulo')
                                ->setRequired(true)
                                ->setPlaceholder('Nome do SubMódulo')));
        // AccessModule
        $this->addField(self::createField('access_module')
                        ->setDbName('access_module')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_INTEGER)
                        ->setDbOrderBySequence(1)
                        ->setDbOrderBySortType('ASC')
                        ->setFkEntity(new \Admin\Entity\AccessModule(false))
                        ->setFkEntityField('cod')
                        ->setFkEntityJoinType(\Kf\Database\Field::DB_JOIN_INNER)
                        ->setDatagridHeader(\Kf\View\Html\Datagrid\Header::create(1, 'Módulo', '30%'))
                        ->setViewComponent(\Kf\View\Html\Select::create('access_module', 'Módulo')
                                ->setRequired(true)));
    }

}
