<?php

namespace Admin\Service;

class Access extends \Kf\Module\Service {

    public function __construct() {
        $this->_model = '\Admin\Model\Access';
    }

    /**
     * Get Access Model
     * @staticvar \Admin\Model\Access $model
     * @return \Admin\Model\Access
     */
    public static function getModel() {
        static $model;
        if (!$model) {
            $model = new \Admin\Model\Access;
        }
        return $model;
    }

    /**
     * Get Module Service
     * @staticvar \Admin\Service\AccessModule $service
     * @return \Admin\Service\AccessModule
     */
    public static function getModuleService() {
        static $serviceModule;
        if (!$serviceModule) {
            $serviceModule = new AccessModule;
        }
        return $serviceModule;
    }

    /**
     * Get Submodule Service
     * @staticvar \Admin\Service\AccessSubmodule $service
     * @return \Admin\Service\AccessSubmodule
     */
    public static function getSubmoduleService() {
        static $serviceSubmodule;
        if (!$serviceSubmodule) {
            $serviceSubmodule = new AccessSubmodule;
        }
        return $serviceSubmodule;
    }

    public function getModulesList() {
        return $this->getModuleService()->fetchAll();
    }

    public function getSubmodulesList($module) {
        return $this->getSubmoduleService()->fetchAll(['access_module' => $module]);
    }

    public function getAccessList($submodule) {
        $data = [];
        foreach ($this->getModel()->getAccessList($submodule) as $row) {
            $data[$row['access']]['cod'] = $row['access'];
            $data[$row['access']]['name'] = $row['access_name'];
            $data[$row['access']]['items'][$row['cod']] = $row['name'];
        }
        return $data;
    }

    public function getControllers($modules = []) {
        return \Kf\System\File::getControllers($modules);
    }

    public function getMethods($controller) {
        return \Kf\System\File::getMethods($controller);
    }

}
