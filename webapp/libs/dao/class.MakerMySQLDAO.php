<?php
class MakerMySQLDAO extends MakerbasePDODAO {

    public function archive($uid) {
        return $this->setIsArchived($uid, true);
    }

    public function unarchive($uid) {
        return $this->setIsArchived($uid, false);
    }

    public function delete($uid) {
        $q = <<<EOD
DELETE FROM makers WHERE uid = :uid
EOD;
        $vars = array (
            ':uid' => $uid
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    private function setIsArchived($uid, $is_archived) {
        $q = <<<EOD
UPDATE makers SET is_archived = :is_archived WHERE uid = :uid
EOD;
        $vars = array (
            ':uid' => $uid,
            ':is_archived' => ($is_archived)?1:0
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function freeze($uid) {
        return $this->setIsFrozen($uid, true);
    }

    public function unfreeze($uid) {
        return $this->setIsFrozen($uid, false);
    }

    private function setIsFrozen($uid, $is_frozen) {
        $q = <<<EOD
UPDATE makers SET is_frozen = :is_frozen WHERE uid = :uid
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

    public function get($uid) {
        $q = "SELECT * FROM makers WHERE uid = :uid";
        $vars = array ( ':uid' => $uid);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $maker = $this->getDataRowAsObject($ps, "Maker");
        if (!isset($maker)) {
            throw new MakerDoesNotExistException('Maker '.$uid.' does not exist.');
        }
        return $maker;
    }

    public function getByID($id) {
        $q = "SELECT * FROM makers WHERE id = :id";
        $vars = array ( ':id' => $id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $maker = $this->getDataRowAsObject($ps, "Maker");
        if (!isset($maker)) {
            throw new MakerDoesNotExistException('Maker does not exist.');
        }
        return $maker;
    }

    public function insert(Maker $maker) {
        $maker->uid = self::generateRandomString(6);
        $q = <<<EOD
INSERT INTO makers (
uid, slug, name, url, avatar_url, autofill_network_id, autofill_network, autofill_network_username
) VALUES (
:uid, :slug, :name, :url, :avatar_url, :autofill_network_id, :autofill_network, :autofill_network_username
)
EOD;
        $vars = array (
            ':uid' => $maker->uid,
            ':slug' => $maker->slug,
            ':name' => $maker->name,
            ':url' => $maker->url,
            ':avatar_url' => $maker->avatar_url,
            ':autofill_network_id' => $maker->autofill_network_id,
            ':autofill_network_username' => $maker->autofill_network_username,
            ':autofill_network' => $maker->autofill_network
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $try_insert = true;
        while ($try_insert) {
            try {
                $ps = $this->execute($q, $vars);
                $try_insert = false;
                $maker->id = $this->getInsertId($ps);
                return $maker;
            } catch (PDOException $e) {
                $message = $e->getMessage();
                if (strpos($message,'Duplicate entry') !== false && strpos($message,'uid') !== false) {
                    $try_insert = true;
                } else {
                    throw new PDOException($message);
                }
            }
        }
    }

    public function update(Maker $maker) {
        $q = <<<EOD
UPDATE makers SET slug = :slug, name = :name, url = :url, avatar_url = :avatar_url
WHERE uid = :uid
EOD;
        $vars = array (
            ':uid' => $maker->uid,
            ':slug' => $maker->slug,
            ':name' => $maker->name,
            ':url' => $maker->url,
            ':avatar_url' => $maker->avatar_url
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function getTotal() {
        $q = <<<EOD
SELECT count(*) AS total FROM makers m
WHERE  is_archived = 0;
EOD;
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q);
        $result = $this->getDataRowAsArray($ps);
        return $result['total'];
    }

    public function getTrendingMakers($in_last_x_days = 3, $limit = 4) {
        $q = <<<EOD
SELECT m.*, count(*) as total_edits FROM action_objects ao
INNER JOIN actions a ON a.id = ao.action_id
INNER JOIN makers m ON ao.object_id = m.id
WHERE DATE(a.time_performed) > DATE_SUB(NOW(), INTERVAL $in_last_x_days DAY)
AND ao.object_type = 'Maker' AND m.is_archived = 0
GROUP BY ao.object_id, ao.object_type
ORDER BY total_edits DESC
LIMIT :limit
EOD;
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $vars = array (
            ':limit' => $limit
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getDataRowsAsObjects($ps, 'Maker'));
    }

    public function getNewestMakers($limit = 4) {
        $q = <<<EOD
SELECT DISTINCT m.* FROM makers m INNER JOIN roles r ON r.maker_id = m.id
WHERE m.is_archived = 0 ORDER BY m.creation_time DESC LIMIT :limit
EOD;
        $vars = array ( ':limit' => $limit);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsObjects($ps, "Maker");
    }

    /**
     * @TODO Optimize this query! It is slow and does not use indexes.
     */
    public function getEventMakers($event) {
        $q = <<<EOD
SELECT DISTINCT m.* FROM makers m INNER JOIN event_makers em ON em.twitter_username = m.autofill_network_username
INNER JOIN roles r ON r.maker_id = m.id
WHERE m.autofill_network = 'twitter' and em.event = :event ORDER BY r.creation_time DESC;
EOD;
        $vars = array (
            ':event' => $event
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsObjects($ps, "Maker");
    }
}
