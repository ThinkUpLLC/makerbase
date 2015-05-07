<?php
class WaitlistMySQLDAO extends MakerbasePDODAO {

    public function insert($network_id, $network, $network_username, $follower_count, $is_verified) {
        $q = <<<EOD
INSERT IGNORE INTO waitlist ( network_id, network, network_username, follower_count, is_verified
) VALUES (
:network_id, :network, :network_username, :follower_count, :is_verified
)
EOD;
        $vars = array (
            ':network_id' => $network_id,
            ':network' => $network,
            ':network_username' => $network_username,
            ':follower_count' => $follower_count,
            ':is_verified' => $is_verified
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getUpdateCount($ps);
    }

    public function archive($network_id, $network) {
        $q = <<<EOD
UPDATE waitlist SET is_archived = 1 WHERE network_id = :network_id AND network = :network
EOD;
        $vars = array (
            ':network_id' => $network_id,
            ':network' => $network
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getUpdateCount($ps);
    }

    public function get($limit = 20) {
        $q = <<<EOD
SELECT * FROM waitlist w
WHERE is_archived = 0 ORDER BY is_verified, follower_count DESC LIMIT :limit;
EOD;
        $vars = array (
            ':limit' => $limit
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsArrays($ps);
    }

    public function getTotal() {
        $q = <<<EOD
SELECT count(*) AS total FROM waitlist w
WHERE is_archived = 0;
EOD;
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q);
        $result = $this->getDataRowAsArray($ps);
        return $result['total'];
    }
}
