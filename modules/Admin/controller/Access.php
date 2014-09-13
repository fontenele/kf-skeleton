<?php

namespace Admin\Controller;

/**
 * Access Controller
 */
class Access extends \Kf\Module\Controller {

    /**
     * Get Service
     * @staticvar \Admin\Service\Access $service
     * @return \Admin\Service\Access
     */
    public static function getService() {
        static $service;
        if (!$service) {
            $service = new \Admin\Service\Access;
        }
        return $service;
    }

    public function listItems() {
        $this->addExtraJsFile('bootstrap/jquery-sortable-min.js');
        $entities = [
            'module' => new \Admin\Entity\AccessModule,
            'submodule' => new \Admin\Entity\AccessSubmodule,
            'access' => new \Admin\Entity\Access
        ];

        $this->view->entities = $entities;
        $this->view->modules = $this->getService()->getModulesList();
        $this->view->controllers = $this->getService()->getControllers();
        return $this->view;
    }

    public function getSubmodulesList() {
        $module = $this->request->get->offsetGet('module');
        $this->view = new \Kf\View\Json;
        $this->view->submodules = $this->getService()->getSubmodulesList($module);
        return $this->view;
    }

    public function getAccessList() {
        $submodule = $this->request->get->offsetGet('submodule');
        $this->view = new \Kf\View\Json;
        $this->view->access = $this->getService()->getAccessList($submodule);
        return $this->view;
    }

    public function saveModule() {
        $this->view = new \Kf\View\Json;
        $row = $this->request->post->getArrayCopy();
        $service = new \Admin\Service\AccessModule;
        $success = $service->save($row);
        if ($success) {
            $this->view->message = "Módulo {$row['name']} salvo com sucesso.";
            $this->view->status = "success";
            $this->view->data = $row;
        } else {
            $this->view->message = "Erro ao tentar salvar Módulo {$row['name']}.";
            $this->view->status = "error";
        }
        return $this->view;
    }

    public function saveSubmodule() {
        $this->view = new \Kf\View\Json;
        $row = $this->request->post->getArrayCopy();
        $service = new \Admin\Service\AccessSubmodule;
        $success = $service->save($row);
        if ($success) {
            $this->view->message = "SubMódulo {$row['name']} salvo com sucesso.";
            $this->view->status = "success";
            $this->view->data = $row;
        } else {
            $this->view->message = "Erro ao tentar salvar SubMódulo {$row['name']}.";
            $this->view->status = "error";
        }
        return $this->view;
    }

    public function saveAccess() {
        $this->view = new \Kf\View\Json;
        $row = $this->request->post->getArrayCopy();
        $service = new \Admin\Service\Access;
        $success = $service->save($row);
        if ($success) {
            $this->view->message = "Acesso {$row['name']} salvo com sucesso.";
            $this->view->status = "success";
            $this->view->data = $row;
        } else {
            $this->view->message = "Erro ao tentar salvar Acesso {$row['name']}.";
            $this->view->status = "error";
        }
        return $this->view;
    }

    public function saveItems() {
        $this->view = new \Kf\View\Json;
        $service = new \Admin\Service\AccessItem;
        $row = $this->request->post->getArrayCopy();
        $errors = 0;
        $data = [];
        // Limpar items existentes
        $service->limparItemsDeSubmodulo($row['submodule']);
        // Salvar items
        foreach ($row['access'] as $access => $items) {
            foreach ($items as $name) {
                $action = explode('::', $name);
                $controller = explode('\\', $action[0]);
                $module = \Kf\System\String::camelToDash($controller[0]);
                $controller = \Kf\System\String::camelToDash($controller[2]);
                $action = \Kf\System\String::camelToDash($action[1]);
                $accessItem = [
                    'cod' => '',
                    'name' => $name,
                    'access' => $access,
                    'path' => "{$module}/{$controller}/{$action}"
                ];
                if (!$service->save($accessItem)) {
                    $errors++;
                }
                $data[] = $accessItem;
            }
        }
        if (!$errors) {
            $this->view->message = "Items de Acesso salvos com sucesso.";
            $this->view->status = "success";
            $this->view->data = $data;
        } else {
            $this->view->message = "Erro ao tentar salvar Items de acesso.";
            $this->view->status = "error";
        }
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
