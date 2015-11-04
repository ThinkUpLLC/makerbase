<?php

class SentTweetMySQLDAO extends MakerbasePDODAO {

    public function insert($twitter_user_id, $twitter_username) {
        $q = <<<EOD
INSERT INTO sent_tweets (
twitter_user_id, twitter_username
) VALUES (
:twitter_user_id, :twitter_username
)
EOD;
        $vars = array (
            ':twitter_user_id' => $twitter_user_id,
            ':twitter_username' => $twitter_username
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        try {
            $ps = $this->execute($q, $vars);
            return $this->getInsertId($ps);
        } catch (PDOException $e) {
            $message = $e->getMessage();
            if (strpos($message,'Duplicate entry') !== false && strpos($message,'twitter_user_id') !== false) {
                return false;
            } else {
                throw new PDOException($message);
            }
        }
    }

    public function hasBeenSent($twitter_user_id) {
        $q = <<<EOD
SELECT * FROM sent_tweets WHERE twitter_user_id = :twitter_user_id;
EOD;
        $vars = array (
            ':twitter_user_id' => $twitter_user_id
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $row = $this->getDataRowAsArray($ps);
        return isset($row);
    }
}