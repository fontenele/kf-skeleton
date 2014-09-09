<?php

namespace Admin\Service;

class AccessSubmodule extends \Kf\Module\Service {

    public function __construct() {
        $this->_model = '\Admin\Model\AccessSubmodule';
    }

    public function teste() {
        x($this);
        xd($this->fetchAll(['access_module' => 2]));
    }

}
