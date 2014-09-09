<?php

namespace Admin\Model;

class AccessUserGroup extends \Kf\Module\Model {

    public function configure() {
        $this->setEntity(new \Admin\Entity\AccessUserGroup);
    }

}
