<?php

namespace Admin\Service;

class Access extends \Kf\Module\Service {

    public function __construct() {
        $this->_model = '\Admin\Model\Access';
    }

    public function getControllers($modules = []) {
        return \Kf\System\File::getControllers($modules);
    }

    public function getMethods($controller) {
        return \Kf\System\File::getMethods($controller);
    }

}
