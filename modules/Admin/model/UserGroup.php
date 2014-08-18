<?php

namespace Admin\Model;

class UserGroup extends \Kf\Module\Model {

    public function configure() {
        $this->setEntity(new \Admin\Entity\UserGroup);
    }

}
