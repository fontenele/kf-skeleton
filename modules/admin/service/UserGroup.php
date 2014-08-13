<?php

namespace Admin\Service;

class UserGroup extends \Kf\Module\Service {

    public function __construct() {
        $this->_model = '\Admin\Model\UserGroup';
    }

}
