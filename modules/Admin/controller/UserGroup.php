<?php

namespace Admin\Controller;

class UserGroup extends \Kf\Module\Controller {

    public function newItem() {
        try {
            $service = new \Admin\Service\UserGroup; // Service
            $entity = new \Admin\Entity\UserGroup; // Entity
            $pk = $entity->getPrimaryKey(); // Primary Key
            // Set values to edit
            $edit = false;
            if ($this->request->get->$pk) {
                $edit = $this->request->get->$pk;
                $row = $service->findOneBy([$pk => $this->request->get->$pk]);
                $entity->setValues($row);
            }
            // Render HTML
            if (!$this->request->isPost()) {
                $this->view->entity = $entity;
                $this->view->edit = $edit;
                return $this->view;
            }
            // Save
            $row = $this->request->post->getArrayCopy();
            $success = $service->save($row);
            // Set alert message and redirect
            if ($success) {
                \Kf\System\Messenger::success("Grupo {$row['name']} salvo com sucesso.");
                $this->redirect('admin/user-group/list-items');
            } else {
                \Kf\System\Messenger::error("Erro ao tentar salvar grupo {$row['name']}.");
                $this->redirect('admin/user-group/new-item');
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function listItems() {
        try {
            $service = new \Admin\Service\UserGroup; // Service
            // Datagrid
            $dg = new \Kf\View\Html\Datagrid\Datagrid('dg-user-group');
            $dg->setEntity(new \Admin\Entity\UserGroup);
            $dg->addHeader(\Kf\View\Html\Datagrid\Header::create('1.9', '', '4%', 'text-center', new \Kf\View\Html\Renderer('\Admin\Controller\UserGroup::dgAccess')));
            $dg->addHeader(\Kf\View\Html\Datagrid\Header::create(4, '', '2%', 'text-center', new \Kf\View\Html\Renderer('\Admin\Controller\UserGroup::dgEdit')));
            $dg->addHeader(\Kf\View\Html\Datagrid\Header::create(5, '', '2%', 'text-center', new \Kf\View\Html\Renderer('\Admin\Controller\UserGroup::dgDelete')));
            $dg->setData($service->fetchAll($this->request->post->getArrayCopy(), $dg->getPaginator()->getRowsPerPage(), $dg->getPaginator()->getActive()));
            $this->view->dg = $dg;
            // Render HTML
            return $this->view;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Render Access Column
     * @param array $row
     * @return string
     */
    public static function dgAccess($row) {
        return '<a title="Ver Acessos" href="' . \Kf\Kernel::$router->basePath . 'admin/user-group/list-access/cod/' . $row['cod'] . '">Acessos</a>';
    }

    /**
     * Render Status Column
     * @param array $row
     * @return string
     */
    public static function dgStatus($row) {
        return $row['status'] == 1 ? '<span title="Ativo">' . \Kf\View\Html\Helper\Icon::get('check-circle-o text-success') . '</span>' : '<span title="Inativo">' . \Kf\View\Html\Helper\Icon::get('ban text-muted') . '</span>';
    }

    /**
     * Render Edit Column
     * @param array $row
     * @return string
     */
    public static function dgEdit($row) {
        return '<a title="Editar grupo" href=' . \Kf\Kernel::$router->basePath . "admin/user-group/new-item/cod/{$row['cod']}>" . \Kf\View\Html\Helper\icon::get('folder-open-o') . '</a>';
    }

    /**
     * Render Delete Column
     * @param array $row
     * @return string
     */
    public static function dgDelete($row) {
        return '<a class="text-danger" title="Excluir grupo" data-confirmation data-placement="left" href="' . \Kf\Kernel::$router->basePath . "admin/user-group/delete-item/cod/{$row['cod']}\">" . \Kf\View\Html\Helper\Icon::get('times-circle-o') . '</a>';
    }

    public function listAccess() {
        $service = new \Admin\Service\UserGroup; // Service
        $serviceItems = new \Admin\Service\AccessItem; // Service
        $entity = new \Admin\Entity\UserGroup; // Entity
        $pk = $entity->getPrimaryKey();
        // Return if primary key wasnt setted
        if (!$this->request->get->$pk) {
            \Kf\System\Messenger::error("Erro ao tentar ver acessos do grupo pois nenhum grupo foi informado.");
            $this->redirect('admin/user-group/list-items');
        }
        $this->view->userGroup = $service->findOneByCod($this->request->get->$pk);
        $this->view->acessos = $serviceItems->listarItems();
        return $this->view;
    }

    public function deleteItem() {
        try {
            $service = new \Admin\Service\UserGroup; // Service
            $entity = new \Admin\Entity\UserGroup; // Entity
            $pk = $entity->getPrimaryKey(); // Primary Key
            // Return if primary key wasnt setted
            if (!$this->request->get->$pk) {
                \Kf\System\Messenger::error("Erro ao tentar excluir grupo pois nenhum grupo foi informado.");
                $this->redirect('admin/user-group/list-items');
            }
            // Delete item
            $success = $service->delete($this->request->get->getArrayCopy());
            // Set alert message
            if ($success) {
                \Kf\System\Messenger::success("Grupo excluído com sucesso.");
            } else {
                \Kf\System\Messenger::error("Erro ao tentar excluir grupo.");
            }
            // Redirect
            $this->redirect('admin/user-group/list-items');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
