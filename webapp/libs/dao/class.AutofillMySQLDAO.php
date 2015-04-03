<?php
class AutofillMySQLDAO extends MakerbasePDODAO {

    public function insert($network_id, $network) {
        $q = <<<EOD
INSERT IGNORE INTO autofills ( network_id, network
) VALUES (
:network_id, :network
)
EOD;
        $vars = array (
            ':network_id' => $network_id,
            ':network' => $network
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getUpdateCount($ps);
    }

    public function doesAutofillExist($network_id, $network) {
        $q = "SELECT * FROM autofills WHERE network_id = :network_id AND network = :network;";
        $vars = array (
            ':network_id' => $network_id,
            ':network' => $network
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $result = $this->getDataRowsAsArrays($ps);
        return (count($result) > 0);
    }
}
