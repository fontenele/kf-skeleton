<?php

namespace Admin\Entity;

class AccessGroup extends \Kf\Module\Entity {

    public function configure($recursive) {
        $this->setTable('public.access_group'); // Table
        $this->setSequence('public.access_group_cod_seq'); // Sequence
        $this->setPrimaryKey('cod'); // Primary Key
        $this->setServiceName('\Admin\Service\AccessGroup'); // Service Name
        // Cod
        $this->addField(self::createField('cod')
                        ->setDbName('cod')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_INTEGER)
                        ->setViewComponent(\Kf\View\Html\InputHidden::create('cod')));
        // Name
        $this->addField(self::createField('name')
                        ->setDbName('name')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(50)
                        ->setDbOrderBySequence(1)
                        ->setDbOrderBySortType('ASC')
                        ->setSearchCriteria(\Kf\Database\Criteria::create(\Kf\Database\Criteria::CONDITION_LIKE))
                        ->setDatagridHeader(\Kf\View\Html\Datagrid\Header::create(1, 'Grupo', '95%'))
                        ->setViewComponent(\Kf\View\Html\InputText::create('name', 'Nome')
                                ->setRequired(true)
                                ->setPlaceholder('Nome do Grupo')));
    }

}
