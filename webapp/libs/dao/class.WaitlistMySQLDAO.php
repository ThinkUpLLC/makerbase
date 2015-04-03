<?php
class WaitlistMySQLDAO extends MakerbasePDODAO {

    public function insert($network_id, $network, $network_username) {
        $q = <<<EOD
INSERT IGNORE INTO waitlist ( network_id, network, network_username
) VALUES (
:network_id, :network, :network_username
)
EOD;
        $vars = array (
            ':network_id' => $network_id,
            ':network' => $network,
            ':network_username' => $network_username
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getUpdateCount($ps);
    }
}
