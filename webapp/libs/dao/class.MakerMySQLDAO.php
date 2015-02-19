<?php
class MakerMySQLDAO extends PDODAO {

    public function get($slug) {
        $q = "SELECT * FROM makers WHERE slug = :slug";
        $vars = array ( ':slug' => $slug);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $maker = $this->getDataRowAsObject($ps, "Maker");
        if (!isset($maker)) {
            throw new MakerDoesNotExistException('Maker '.$slug.' does not exist.');
        }
        return $maker;
    }

    public function listMakers($limit = 50) {
        $q = "SELECT * FROM makers LIMIT :limit;";
        $vars = array ( ':limit' => $limit);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsObjects($ps, "Maker");
    }
}
