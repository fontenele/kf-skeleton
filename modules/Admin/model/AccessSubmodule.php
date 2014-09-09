<?php

namespace Admin\Model;

class AccessSubmodule extends \Kf\Module\Model {

    public function configure() {
        $this->setEntity(new \Admin\Entity\AccessSubmodule);
    }

}
