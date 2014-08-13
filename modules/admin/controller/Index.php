<?php

namespace Admin\Controller;

class Index extends \Kf\Module\Controller {

    public function init() {
        
    }

    public function index() {
        try {
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function error404() {
        try {
            $this->view->template = 'public/themes/' . \Kf\Kernel::$config['system']['view']['theme'] . '/view/' . \Kf\Kernel::$config['system']['view']['error404'];
            $this->view->config = \Kf\Kernel::$config;
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public function errorDefault() {
        try {
            $this->view->template = 'public/themes/' . \Kf\Kernel::$config['system']['view']['theme'] . '/view/' . \Kf\Kernel::$config['system']['view']['errorDefault'];
            $this->view->config = \Kf\Kernel::$config;
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
