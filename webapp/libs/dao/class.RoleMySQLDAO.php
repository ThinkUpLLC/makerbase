<?php
class RoleMySQLDAO extends PDODAO {

    public function getByMaker($maker_id) {
        $q = "SELECT r.*, p.* FROM roles r INNER JOIN products p ON r.product_id = p.id WHERE maker_id = :maker_id";
        $vars = array ( ':maker_id' => $maker_id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);
        $roles = array();
        foreach ($rows as $row) {
            $role = new Role($row);
            $product = new Product($row);
            $role->product = $product;
            $roles[] = $role;
        }
        return $roles;
    }
}
