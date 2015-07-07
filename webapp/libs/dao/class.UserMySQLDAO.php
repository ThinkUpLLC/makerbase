<?php
class UserMySQLDAO extends MakerbasePDODAO {

    public function insert(User $user) {
        $user->uid = self::generateRandomString(6);
        $q = <<<EOD
INSERT INTO users (
uid, name, url, avatar_url, twitter_user_id, twitter_username, twitter_oauth_access_token,
twitter_oauth_access_token_secret, last_login_time
) VALUES (
:uid, :name, :url, :avatar_url, :twitter_user_id, :twitter_username, :twitter_oauth_access_token,
:twitter_oauth_access_token_secret, NOW()
)
EOD;
        $vars = array (
            ':uid' => $user->uid,
            ':name' => $user->name,
            ':url' => $user->url,
            ':avatar_url' => $user->avatar_url,
            ':twitter_user_id' => $user->twitter_user_id,
            ':twitter_username' => $user->twitter_username,
            'twitter_oauth_access_token' => $user->twitter_oauth_access_token,
            ':twitter_oauth_access_token_secret' => $user->twitter_oauth_access_token_secret
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);

        $try_insert = true;
        while ($try_insert) {
            try {
                $ps = $this->execute($q, $vars);
                $try_insert = false;
                $user->id = $this->getInsertId($ps);
                return $user;
            } catch (PDOException $e) {
                $message = $e->getMessage();
                if (strpos($message,'Duplicate entry') !== false && strpos($message,'uid') !== false) {
                    $try_insert = true;
                } elseif (strpos($message,'Duplicate entry') !== false
                    && strpos($message,'twitter_user_id') !== false) {
                    throw new DuplicateUserException($message);
                } else {
                    throw new PDOException($message);
                }
            }
        }
    }

    public function get($uid) {
        $q = "SELECT * FROM users WHERE uid = :uid";
        $vars = array ( ':uid' => $uid);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $user = $this->getDataRowAsObject($ps, "User");
        if (!isset($user)) {
            throw new UserDoesNotExistException('User '.$uid.' does not exist.');
        }
        return $user;
    }

    public function getByTwitterUserId($twitter_user_id) {
        $q = "SELECT * FROM users WHERE twitter_user_id = :twitter_user_id";
        $vars = array ( ':twitter_user_id' => $twitter_user_id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $user = $this->getDataRowAsObject($ps, "User");
        if (!isset($user)) {
            throw new UserDoesNotExistException('User '.$twitter_user_id.' does not exist.');
        }
        return $user;
    }

    public function getFollowersWhoAreUsers($twitter_user_id) {
        $q = <<<EOD
SELECT u.twitter_username AS follower_username FROM users u INNER JOIN waitlist_follows f ON u.twitter_user_id = f.follower_id
WHERE f.user_id = :twitter_user_id
EOD;
        $vars = array ( ':twitter_user_id' => $twitter_user_id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $users = $this->getDataRowsAsArrays($ps);
        return $users;
    }

    public function updateLastLogin(User $user) {
        $q = "UPDATE users SET last_login_time = CURRENT_TIMESTAMP WHERE twitter_user_id = :twitter_user_id";
        $vars = array ( ':twitter_user_id' => $user->twitter_user_id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $update_count = $this->getUpdateCount($ps);
        if ($update_count == 0) {
            throw new UserDoesNotExistException('User '.$user->twitter_user_id.' does not exist.');
        }
        return $update_count;
    }

    public function getTotal() {
        $q = <<<EOD
SELECT count(*) AS total FROM users u;
EOD;
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q);
        $result = $this->getDataRowAsArray($ps);
        return $result['total'];
    }

    public function freeze($uid) {
        return $this->setIsFrozen($uid, true);
    }

    public function unfreeze($uid) {
        return $this->setIsFrozen($uid, false);
    }

    private function setIsFrozen($uid, $is_frozen) {
        $q = <<<EOD
UPDATE users SET is_frozen = :is_frozen WHERE uid = :uid
EOD;
        $vars = array (
            ':uid' => $uid,
            ':is_frozen' => ($is_frozen)?1:0
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }
}


