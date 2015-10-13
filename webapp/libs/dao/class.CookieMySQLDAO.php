<?php

class CookieMySQLDAO extends MakerbasePDODAO {
    /**
     * Generate a unique cookie for a user.
     * @param str $email Email for which to generate cookie
     * @return str Cookie generated
     */
    public function generateForUser($uid) {
        // We generate a cookie string using hash() and the UID, time, some randomness
        // We try three times to insert it, because of the unique constraint on the table.  But once will work
        // 99.9999942% of the time.
        $cookie = null;
        for ($i=0; $i<3; $i++) {
            $try = hash('sha256', (time() . $uid . mt_rand()));
            $q = "INSERT INTO cookies (user_uid, cookie) VALUES (:uid, :cookie)";
            $vars = array(
                ':uid' => $uid,
                ':cookie' => $try
            );
            if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
            try {
                $query = self::mergeSQLVars($q, $vars);
                $res = $this->execute($q, $vars);

                if ($this->getInsertCount($res) > 0) {
                    $cookie = $try;
                    break;
                }
            } catch (PDOException $e) {
                //Duplicate entry
                $message = $e->getMessage();
                if (strpos($message,'Duplicate entry') !== false && strpos($message,"for key 'cookie'") !== false) {
                    $try = null;
                    //do nothing, loop will come back around
                } else {
                    throw new PDOException($message);
                }
            }
        }
        return $cookie;
    }

    /**
     * Delete all cookies for a given user.
     * @param str $uid UID of the user to delete cookies for
     * @return bool Did we delete them?
     */
    public function deleteByUser($user_uid) {
        $q = "DELETE FROM cookies WHERE user_uid = :user_uid ";
        $vars = array(
            ':user_uid' => (string) $user_uid
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $res = $this->execute($q, $vars);
        return $this->getUpdateCount($res) > 0;
    }

    /**
     * Delete a given cookie.
     * @param str $cookie What cookie record to delete
     * @return bool Did we delete it?
     */
    public function deleteByCookie($cookie) {
        $q = "DELETE FROM cookies WHERE cookie = :cookie ";
        $vars = array(
            ':cookie' => (string) $cookie
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $res = $this->execute($q, $vars);
        return $this->getUpdateCount($res) > 0;
    }

    /**
     * Get user's UID associated with a cookie.
     * @param str $cookie Cookie we are attempting to find.
     * @return str Associated UID or null
     */
    public function getUIDByCookie($cookie) {
        $q = "SELECT user_uid FROM cookies WHERE cookie = :cookie ";
        $vars = array(
            ':cookie' => (string) $cookie
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $res = $this->execute($q, $vars);
        $data = $this->getDataRowAsArray($res);
        if ($data) {
            return $data['user_uid'];
        }
        return null;
    }
}