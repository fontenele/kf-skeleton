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
        // Key
        $this->addField(self::createField('key')
                        ->setDbName('key')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(200)
                        ->setDbOrderBySequence(1)
                        ->setDbOrderBySortType('ASC')
                        ->setSearchCriteria(\Kf\Database\Criteria::create(\Kf\Database\Criteria::CONDITION_LIKE))
                        ->setDatagridHeader(\Kf\View\Html\Datagrid\Header::create(1, 'Chave', '45%'))
                        ->setViewComponent(\Kf\View\Html\InputText::create('key', 'Chave')
                                ->setRequired(true)
                                ->setPlaceholder('Nome da Chave de acesso')));
        // Description
        $this->addField(self::createField('description')
                        ->setDbName('description')
                        ->setDbType(\Kf\Database\Field::DB_TYPE_VARCHAR)
                        ->setDbMaxLength(300)
                        ->setSearchCriteria(\Kf\Database\Criteria::create(\Kf\Database\Criteria::CONDITION_LIKE))
                        ->setDatagridHeader(\Kf\View\Html\Datagrid\Header::create(1, 'Descrição', '45%'))
                        ->setViewComponent(\Kf\View\Html\InputText::create('description', 'Descrição')
                                ->setRequired(true)
                                ->setPlaceholder('Descrição da Chave de acesso')));
    }

}
