<?php
class AutofillMySQLDAO extends MakerbasePDODAO {

    private function insert($network_id, $network, $network_username, $maker_id, $product_id) {
        $q = <<<EOD
INSERT IGNORE INTO autofills (
    network_id, network, network_username, maker_id, product_id
) VALUES (
    :network_id, :network, :network_username, :maker_id, :product_id
)
EOD;
        $vars = array (
            ':network_id' => $network_id,
            ':network' => $network,
            ':network_username' => $network_username,
            ':maker_id' => $maker_id,
            ':product_id' => $product_id
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getUpdateCount($ps);
    }

    public function insertMakerAutofill($network_id, $network, $network_username, $maker_id) {
        return self::insert($network_id, $network, $network_username, $maker_id, null);
    }

    public function insertProductAutofill($network_id, $network, $network_username, $product_id) {
        return self::insert($network_id, $network, $network_username, null, $product_id);
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
