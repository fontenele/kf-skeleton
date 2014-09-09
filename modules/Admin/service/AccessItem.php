<?php

namespace Admin\Service;

class AccessItem extends \Kf\Module\Service {

    public function __construct() {
        $this->_model = '\Admin\Model\AccessItem';
    }

    public function limparItemsDeSubmodulo($submodulo) {
        return $this->model()->limparItemsDeSubmodulo($submodulo);
    }

    public function listarItems($modulo = null, $submodulo = null, $acesso = null) {
        $items = [];
        foreach ($this->model()->listarItems($modulo, $submodulo, $acesso) as $item) {
            if (!isset($items[$item['access_module']])) {
                $items[$item['access_module']] = [
                    'cod' => $item['access_module'],
                    'name' => $item['access_module_name'],
                    'submodules' => []
                ];
            }
            if (!isset($items[$item['access_module']]['submodules'][$item['access_submodule']])) {
                $items[$item['access_module']]['submodules'][$item['access_submodule']] = [
                    'cod' => $item['access_submodule'],
                    'name' => $item['access_submodule_name'],
                    'access' => []
                ];
            }
            if (!isset($items[$item['access_module']]['submodules'][$item['access_submodule']]['access'][$item['access']])) {
                $items[$item['access_module']]['submodules'][$item['access_submodule']]['access'][$item['access']] = [
                    'cod' => $item['access'],
                    'name' => $item['access_name'],
                    'items' => []
                ];
            }

            $items[$item['access_module']]['submodules'][$item['access_submodule']]['access'][$item['access']]['items'][$item['access_item']] = [
                'cod' => $item['access_item'],
                'name' => $item['access_item_name']
            ];
        }
        //xd($items);
        return $items;
    }

}
