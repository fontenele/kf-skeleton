<?php

namespace Admin\Controller;

class Access extends \Kf\Module\Controller {

    public function listItems() {
        return $this->view;
    }

    public function newItem() {
        xd('new item');
        return $this->view;
    }

}
