<?php

namespace Admin\Model;

class Access extends \Kf\Module\Model {

    public function configure() {
        $this->setEntity(new \Admin\Entity\Access);
    }

}
