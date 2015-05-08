<?php
class WaitlistFollowMySQLDAO extends MakerbasePDODAO {

    public function insert($follower_id, $user_id, $network) {
        $q = <<<EOD
INSERT IGNORE INTO waitlist_follows (
user_id, follower_id, network
) VALUES (
:user_id, :follower_id, :network
)
EOD;
        $vars = array (
            ':user_id' => $user_id,
            ':follower_id' => $follower_id,
            ':network' => $network
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getInsertCount($ps);
    }
}
