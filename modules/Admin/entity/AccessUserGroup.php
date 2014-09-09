<?php

namespace Admin\Entity;

class AccessUserGroup extends \Kf\Module\Entity {

    public function configure($recursive) {
        $this->setTable('public.access_user_group'); // Table
        $this->setSequence('public.access_user_group_cod_seq'); // Sequence
        $this->setPrimaryKey('cod'); // Primary Key
        $this->setServiceName('\Admin\Service\AccessUserGroup'); // Service Name
        // Cod
        $this->addField(self::createField('cod')
                        ->setDbName('cod')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_INTEGER)
                        ->setViewComponent(\Kf\View\Html\InputHidden::create('cod')));
        // Access
        $this->addField(self::createField('access')
                        ->setDbName('access')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_INTEGER)
                        ->setDbOrderBySequence(2)
                        ->setDbOrderBySortType('ASC')
                        ->setFkEntity(new \Admin\Entity\Access(false))
                        ->setFkEntityField('cod')
                        ->setFkEntityJoinType(\Kf\Database\Field::DB_JOIN_INNER)
                        ->setDatagridHeader(\Kf\View\Html\Datagrid\Header::create(2, 'Acesso', '60%'))
                        ->setViewComponent(\Kf\View\Html\Select::create('access', 'Acesso')
                                ->setRequired(true)));
        // UserGroup
        $this->addField(self::createField('user_group')
                        ->setDbName('user_group')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_INTEGER)
                        ->setDbOrderBySequence(1)
                        ->setDbOrderBySortType('ASC')
                        ->setFkEntity(new \Admin\Entity\UserGroup(false))
                        ->setFkEntityField('cod')
                        ->setFkEntityJoinType(\Kf\Database\Field::DB_JOIN_INNER)
                        ->setDatagridHeader(\Kf\View\Html\Datagrid\Header::create(1, 'Grupo de Usuário', '30%'))
                        ->setViewComponent(\Kf\View\Html\Select::create('user_group', 'Grupo de Usuário')
                                ->setRequired(true)));
    }

}
