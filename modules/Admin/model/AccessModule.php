<?php

namespace Admin\Model;

class AccessModule extends \Kf\Module\Model {

    public function configure() {
        $this->setEntity(new \Admin\Entity\AccessModule);
    }

}
