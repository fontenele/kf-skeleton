<?php

namespace Admin\Model;

class AccessItem extends \Kf\Module\Model {

    public function configure() {
        $this->setEntity(new \Admin\Entity\AccessItem);
    }

    public function limparItemsDeSubmodulo($submodulo) {
        $dml = <<<DML
                delete from public.access_item where cod in (
                    select
                        j1.cod
                    from
                        public.access_item as j1
                        inner join public.access j2 on j1.access = j2.cod
                    where
                        1 = 1
                        and j2.access_submodule = :submodule
                    )
DML;
        $input['submodule'] = $submodulo;
        return $this->deleteBySql($dml, $input);
    }

    public function listarItems($modulo = null, $submodulo = null, $acesso = null) {
        $where = '';
        $input = [];

        if ($modulo) {
            $where.= " AND j4.cod = :modulo ";
            $input[':modulo'] = $modulo;
        }
        if ($submodulo) {
            $where.= " AND j3.cod = :submodulo ";
            $input[':submodulo'] = $submodulo;
        }
        if ($acesso) {
            $where.= " AND j2.cod = :acesso ";
            $input[':acesso'] = $acesso;
        }
        $dml = <<<DML
                select
                    j4.cod as access_module,
                    j4.name as access_module_name,
                    j3.cod as access_submodule,
                    j3.name as access_submodule_name,
                    j2.cod as access,
                    j2.name as access_name,
                    j1.cod as access_item,
                    j1.name as access_item_name
                from
                    public.access_item as j1
                    inner join public.access j2 on j1.access = j2.cod
                    inner join public.access_submodule j3 on j2.access_submodule = j3.cod
                    inner join public.access_module j4 on j3.access_module = j4.cod
                where
                    1 = 1
                    {$where}
DML;
        return $this->fetchAllBySql($dml, $input);
    }

}
