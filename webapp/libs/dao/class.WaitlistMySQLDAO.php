<?php
class WaitlistMySQLDAO extends MakerbasePDODAO {

    public function insert($network_id, $network, $network_username, $follower_count, $is_verified,
        $twitter_oauth_access_token, $twitter_oauth_access_token_secret) {
        $q = <<<EOD
INSERT IGNORE INTO waitlist ( network_id, network, network_username, follower_count, is_verified,
    twitter_oauth_access_token, twitter_oauth_access_token_secret
) VALUES (
    :network_id, :network, :network_username, :follower_count, :is_verified,
    :twitter_oauth_access_token, :twitter_oauth_access_token_secret
)
EOD;
        $vars = array (
            ':network_id' => $network_id,
            ':network' => $network,
            ':network_username' => $network_username,
            ':follower_count' => $follower_count,
            ':twitter_oauth_access_token' => $twitter_oauth_access_token,
            ':twitter_oauth_access_token_secret' => $twitter_oauth_access_token_secret,
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

    public function getWaitlistersWithZeroFollowers($limit = 20) {
        $q = <<<EOD
SELECT * FROM waitlist w
WHERE is_archived = 0 AND follower_count = 0 LIMIT :limit;
EOD;
        $vars = array (
            ':limit' => $limit
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsArrays($ps);
    }

    public function setFollowerCount($network_id, $network, $follower_count) {
        $q = <<<EOD
UPDATE  waitlist SET follower_count = :follower_count WHERE network_id = :network_id AND network = :network
EOD;
        $vars = array (
            ':network_id' => $network_id,
            ':network' => $network,
            ':follower_count' => $follower_count,
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getUpdateCount($ps);
    }
}
