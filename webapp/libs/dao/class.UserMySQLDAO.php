<?php
class UserMySQLDAO extends PDODAO {

    public function insert(User $user) {
        $q = <<<EOD
INSERT INTO users (
name, url, avatar_url, twitter_user_id, twitter_username, twitter_oauth_access_token, twitter_oauth_access_token_secret
) VALUES (
:name, :url, :avatar_url, :twitter_user_id, :twitter_username, :twitter_oauth_access_token,
:twitter_oauth_access_token_secret
)
EOD;
        $vars = array (
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
        try {
            $ps = $this->execute($q, $vars);
            return $this->getInsertId($ps);
        } catch (PDOException $e) {
            $message = $e->getMessage();
            if (strpos($message,'Duplicate entry') !== false && strpos($message,'twitter_user_id') !== false) {
                throw new DuplicateUserException($message);
            } else {
                throw new PDOException($message);
            }
        }
    }

    public function get($twitter_user_id) {
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

    public function updateLastLogin(User $user) {
        $q = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE twitter_user_id = :twitter_user_id";
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
}
