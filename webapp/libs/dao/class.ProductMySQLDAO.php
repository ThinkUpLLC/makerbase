<?php
class ProductMySQLDAO extends PDODAO {

    public function get($slug) {
        $q = "SELECT * FROM products WHERE slug = :slug";
        $vars = array ( ':slug' => $slug);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $maker = $this->getDataRowAsObject($ps, "Product");
        if (!isset($maker)) {
            throw new ProductDoesNotExistException('Product '.$slug.' does not exist.');
        }
        return $maker;
    }
}
