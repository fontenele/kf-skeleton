<?php

namespace Admin\Controller;

class Access {

    public function listItems() {
        xd('list items');
        return $this->view;
    }

    public function newItem() {
        xd('new item');
        return $this->view;
    }

}
