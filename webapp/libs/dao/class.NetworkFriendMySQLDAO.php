<?php

class NetworkFriendMySQLDAO extends MakerbasePDODAO {

    public function insert($user_id, $friend_id, $network) {
        $q = <<<EOD
INSERT IGNORE INTO network_friends (
user_id, friend_id, network
) VALUES (
:user_id, :friend_id, :network
)
EOD;
        $vars = array (
            ':user_id' => $user_id,
            ':friend_id' => $friend_id,
            ':network' => $network
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getInsertCount($ps);
    }
}
