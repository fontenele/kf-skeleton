<?php

namespace Admin\Model;

class Access extends \Kf\Module\Model {

    public function configure() {
        $this->setEntity(new \Admin\Entity\Access);
    }

    public function getAccessList($submodule) {
        $dml = new \Kf\Database\Dml();
        $dml->select(['j1.cod', 'j1.name', 'j2.cod as access', 'j2.name as access_name'])
                ->from('public.access', 'j2')
                ->join(\Kf\Database\Field::DB_JOIN_LEFT, 'access_item j1', 'j2.cod', 'j1.access')
                ->where(['j2.access_submodule' => [':submodule', \Kf\Database\Criteria::CONDITION_EQUAL]])
                ->orderBy(['j1.access' => 'ASC']);
        $dml->input['submodule'] = $submodule;
        return $this->fetchAllBySql($dml->query, $dml->input);
    }

}
