<?php

namespace Admin\Controller;

/**
 * Access Controller
 */
class Access extends \Kf\Module\Controller {

    public function listItems() {
        $this->addExtraJsFile('bootstrap/jquery-sortable-min.js');
        $service = new \Admin\Service\Access;
        $this->view->controllers = $service->getControllers();
        return $this->view;
    }
    
    public function getMethods() {
        $controller = $this->request->get->offsetGet('controller');
        $service = new \Admin\Service\Access;
        $this->view = new \Kf\View\Json;
        $this->view->methods = $service->getMethods($controller);
        return $this->view;
    }

    public function newItem() {
        xd('new item');
        return $this->view;
    }

}
