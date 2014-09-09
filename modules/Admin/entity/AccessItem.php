<?php

namespace Admin\Entity;

class AccessItem extends \Kf\Module\Entity {

    public function configure($recursive) {
        $this->setTable('public.access_item'); // Table
        $this->setSequence('public.access_item_cod_seq'); // Sequence
        $this->setPrimaryKey('cod'); // Primary Key
        $this->setServiceName('\Admin\Service\AccessItem'); // Service Name
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
                        ->setDatagridHeader(\Kf\View\Html\Datagrid\Header::create(2, 'Item', '60%'))
                        ->setViewComponent(\Kf\View\Html\InputText::create('name', 'Item')
                                ->setRequired(true)
                                ->setPlaceholder('Nome do Item de Acesso')));
        // Access
        $this->addField(self::createField('access')
                        ->setDbName('access')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_INTEGER)
                        ->setDbOrderBySequence(1)
                        ->setDbOrderBySortType('ASC')
                        ->setFkEntity(new \Admin\Entity\Access(false))
                        ->setFkEntityField('cod')
                        ->setFkEntityJoinType(\Kf\Database\Field::DB_JOIN_INNER)
                        ->setDatagridHeader(\Kf\View\Html\Datagrid\Header::create(1, 'Acesso', '30%'))
                        ->setViewComponent(\Kf\View\Html\Select::create('access', 'Acesso')
                                ->setRequired(true)));
    }

}
