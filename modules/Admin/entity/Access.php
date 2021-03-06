<?php

namespace Admin\Entity;

class Access extends \Kf\Module\Entity {

    public function configure($recursive) {
        $this->setTable('public.access'); // Table
        $this->setSequence('public.access_cod_seq'); // Sequence
        $this->setPrimaryKey('cod'); // Primary Key
        $this->setServiceName('\Admin\Service\Access'); // Service Name
        // Cod
        $this->addField(self::createField('cod')
                        ->setDbName('cod')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_INTEGER)
                        ->setViewComponent(\Kf\View\Html\InputHidden::create('cod')));
        // Name
        $this->addField(self::createField('name')
                        ->setDbName('name')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(250)
                        ->setDbOrderBySequence(2)
                        ->setDbOrderBySortType('ASC')
                        ->setSearchCriteria(\Kf\Database\Criteria::create(\Kf\Database\Criteria::CONDITION_LIKE))
                        ->setDatagridHeader(\Kf\View\Html\Datagrid\Header::create(2, 'Acesso', '60%'))
                        ->setViewComponent(\Kf\View\Html\InputText::create('name', 'Acesso')
                                ->setRequired(true)
                                ->setPlaceholder('Nome do Acesso')));
        // AccessModule
        $this->addField(self::createField('access_submodule')
                        ->setDbName('access_submodule')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_INTEGER)
                        ->setDbOrderBySequence(1)
                        ->setDbOrderBySortType('ASC')
                        ->setFkEntity(new \Admin\Entity\AccessSubmodule(false))
                        ->setFkEntityField('cod')
                        ->setFkEntityJoinType(\Kf\Database\Field::DB_JOIN_INNER)
                        ->setDatagridHeader(\Kf\View\Html\Datagrid\Header::create(1, 'SubMódulo', '30%'))
                        ->setViewComponent(\Kf\View\Html\Select::create('access_submodule', 'SubMódulo')
                                ->setRequired(true)));
    }

}
