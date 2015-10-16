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

    public function getByAutofill($autofill_network, $autofill_network_id) {
        $q = <<<EOD
SELECT * FROM makers WHERE autofill_network = :autofill_network AND autofill_network_id = :autofill_network_id
EOD;
        $vars = array (
            ':autofill_network' => $autofill_network,
            ':autofill_network_id' => $autofill_network_id,
        );
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

    public function getTrendingMakers($in_last_x_days = 3, $limit = 4, $projects_per_maker = 4) {
        $q = <<<EOD
CREATE OR REPLACE VIEW makers_trending AS
SELECT ao.object_id, ao.object_type, COUNT( * ) AS total_edits
FROM action_objects ao
INNER JOIN actions a ON a.id = ao.action_id
INNER JOIN makers m ON ao.object_id = m.id
AND ao.object_type =  'Maker'
WHERE DATE( a.time_performed ) > DATE_SUB( NOW( ) , INTERVAL $in_last_x_days DAY )
AND m.is_archived = 0
GROUP BY ao.object_id, ao.object_type
ORDER BY total_edits DESC
LIMIT :limit;
EOD;

        $vars = array (
            ':limit' => $limit,
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);

        $q = <<<EOD
SELECT m.uid AS maker_uid, m.slug AS maker_slug, m.avatar_url AS maker_avatar_url, m.name AS maker_name,
p.uid AS product_uid, p.slug AS product_slug, p.avatar_url AS product_avatar_url, p.name AS product_name
FROM makers m
LEFT JOIN roles r ON r.maker_id = m.id
LEFT JOIN products p ON r.product_id = p.id
INNER JOIN makers_trending mt ON mt.object_id = m.id
ORDER BY m.uid
EOD;

        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);

        $makers = array();
        $current_maker = null;
        $defaults = array('id'=>null, 'creation_time'=>null, 'url'=>null, 'is_archived'=>0, 'is_frozen'=>0,
            'autofill_network_id'=>null, 'autofill_network'=>null, 'autofill_network_username'=>null,
            'description'=>'');

        foreach ($rows as $row) {
            if (!isset($current_maker) || $current_maker->uid !== $row['maker_uid']) {
                if (isset($current_maker)) {
                    $makers[] = $current_maker;
                }
                $maker_vals = array(
                    'uid'=>$row['maker_uid'],
                    'slug'=>$row['maker_slug'],
                    'avatar_url'=>$row['maker_avatar_url'],
                    'name'=>$row['maker_name']
                );
                $current_maker = new Maker(array_merge($maker_vals, $defaults));
                $current_maker->products = array();
            }
            if (isset($row['product_uid'])) {
                $product_vals = array(
                    'uid'=>$row['product_uid'],
                    'slug'=>$row['product_slug'],
                    'avatar_url'=>$row['product_avatar_url'],
                    'name'=>$row['product_name']
                );
                if (count($current_maker->products) < $projects_per_maker) {
                    $current_maker->products[] = new Product(array_merge($product_vals, $defaults));
                }
            }
        }
        $makers[] = $current_maker;
        return array_slice($makers, 0, $limit);
    }

    public function hasEventPermission($event_slug, User $user) {
        $q = <<<EOD
SELECT * FROM event_permissions
WHERE twitter_username = :twitter_username AND event = :event;
EOD;
        $vars = array (
            ':twitter_username' => $user->twitter_username,
            ':event' => $event_slug

        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $results = $this->getDataRowsAsArrays($ps);
        return (count($results) > 0);
    }

    public function isAttendingEvent($event_slug, User $user) {
        $q = <<<EOD
SELECT * FROM event_makers
WHERE maker_id = :maker_id AND event_slug = :event;
EOD;
        $vars = array (
            ':maker_id' => $user->maker_id,
            ':event' => $event_slug

        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $results = $this->getDataRowsAsArrays($ps);
        return (count($results) > 0);
    }

    public function setIsAttendingEvent(Maker $maker, $event_slug) {
        $q = <<<EOD
INSERT IGNORE INTO event_makers (maker_id, event_slug, is_speaker, speak_date)
VALUES (:maker_id, :event_slug, 0, null);
EOD;
        $vars = array (
            ':maker_id' => $maker->id,
            ':event_slug' => $event_slug

        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function getNewestMakers($limit = 4, $projects_per_maker = 4) {
        $q = <<<EOD
SELECT DISTINCT m.uid AS maker_uid, m.slug AS maker_slug, m.avatar_url AS maker_avatar_url, m.name AS maker_name,
p.uid AS product_uid, p.slug AS product_slug, p.avatar_url AS product_avatar_url, p.name AS product_name FROM makers m
INNER JOIN roles r ON r.maker_id = m.id
LEFT JOIN products p ON r.product_id = p.id
WHERE m.is_archived = 0 ORDER BY m.creation_time DESC LIMIT :limit
EOD;
        $vars = array ( ':limit' => ($limit * $projects_per_maker));
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);

        $makers = array();
        $current_maker = null;
        $defaults = array('id'=>null, 'creation_time'=>null, 'url'=>null, 'is_archived'=>0, 'is_frozen'=>0,
            'autofill_network_id'=>null, 'autofill_network'=>null, 'autofill_network_username'=>null,
            'description'=>'');

        foreach ($rows as $row) {
            if (!isset($current_maker) || $current_maker->uid !== $row['maker_uid']) {
                if (isset($current_maker)) {
                    $makers[] = $current_maker;
                }
                $maker_vals = array(
                    'uid'=>$row['maker_uid'],
                    'slug'=>$row['maker_slug'],
                    'avatar_url'=>$row['maker_avatar_url'],
                    'name'=>$row['maker_name']
                );
                $current_maker = new Maker(array_merge($maker_vals, $defaults));
                $current_maker->products = array();
            }
            if (isset($row['product_uid'])) {
                $product_vals = array(
                    'uid'=>$row['product_uid'],
                    'slug'=>$row['product_slug'],
                    'avatar_url'=>$row['product_avatar_url'],
                    'name'=>$row['product_name']
                );
                if (count($current_maker->products) < $projects_per_maker) {
                    $current_maker->products[] = new Product(array_merge($product_vals, $defaults));
                }
            }
        }
        $makers[] = $current_maker;
        return array_slice($makers, 0, $limit);
    }

    public function getEventMakers($event_slug, $projects_per_maker) {
        $q = <<<EOD
SELECT m.uid AS maker_uid, m.slug AS maker_slug, m.avatar_url AS maker_avatar_url, m.name AS maker_name,
p.uid AS product_uid, p.slug AS product_slug, p.avatar_url AS product_avatar_url, p.name AS product_name
FROM makers m INNER JOIN event_makers em ON em.maker_id = m.id
LEFT JOIN roles r ON r.maker_id = m.id
LEFT JOIN products p ON r.product_id = p.id
WHERE m.is_archived = 0 AND (r.is_archived = 0 OR r.is_archived IS NULL)
AND em.event_slug = :event_slug AND em.is_speaker = 0
ORDER BY m.id DESC,
ISNULL(r.start) ASC, ISNULL(r.end) DESC, end DESC, start ASC;
EOD;
        $vars = array (
            ':event_slug' => $event_slug
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);

        $makers = array();
        $current_maker = null;
        $defaults = array('id'=>null, 'creation_time'=>null, 'url'=>null, 'is_archived'=>0, 'is_frozen'=>0,
            'autofill_network_id'=>null, 'autofill_network'=>null, 'autofill_network_username'=>null,
            'description'=>'');

        foreach ($rows as $row) {
            if (!isset($current_maker) || $current_maker->uid !== $row['maker_uid']) {
                if (isset($current_maker)) {
                    $makers[] = $current_maker;
                }
                $maker_vals = array(
                    'uid'=>$row['maker_uid'],
                    'slug'=>$row['maker_slug'],
                    'avatar_url'=>$row['maker_avatar_url'],
                    'name'=>$row['maker_name']
                );
                $current_maker = new Maker(array_merge($maker_vals, $defaults));
                $current_maker->products = array();
            }
            if (isset($row['product_uid'])) {
                $product_vals = array(
                    'uid'=>$row['product_uid'],
                    'slug'=>$row['product_slug'],
                    'avatar_url'=>$row['product_avatar_url'],
                    'name'=>$row['product_name']
                );
                if (count($current_maker->products) < $projects_per_maker) {
                    $current_maker->products[] = new Product(array_merge($product_vals, $defaults));
                }
            }
        }
        $makers[] = $current_maker;
        return $makers;
    }

    public function getEventSpeakers($event_slug, $projects_per_speaker, $day_speaking) {
        $q = <<<EOD
SELECT m.uid AS maker_uid, m.slug AS maker_slug, m.avatar_url AS maker_avatar_url, m.name AS maker_name,
p.uid AS product_uid, p.slug AS product_slug, p.avatar_url AS product_avatar_url, p.name AS product_name
FROM makers m INNER JOIN event_makers em ON em.maker_id = m.id
LEFT JOIN roles r ON r.maker_id = m.id
LEFT JOIN products p ON r.product_id = p.id
WHERE m.is_archived = 0 AND (r.is_archived = 0 OR r.is_archived IS NULL)
AND em.event_slug = :event_slug AND em.is_speaker = 1
AND DATE(em.speak_date) = :day_speaking
ORDER BY em.speak_date ASC,
ISNULL(r.start) ASC, ISNULL(r.end) DESC, end DESC, start ASC;
EOD;
        $vars = array (
            ':event_slug' => $event_slug,
            ':day_speaking' => $day_speaking
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);

        $makers = array();
        $current_maker = null;
        $defaults = array('id'=>null, 'creation_time'=>null, 'url'=>null, 'is_archived'=>0, 'is_frozen'=>0,
            'autofill_network_id'=>null, 'autofill_network'=>null, 'autofill_network_username'=>null,
            'description'=>'');

        foreach ($rows as $row) {
            if (!isset($current_maker) || $current_maker->uid !== $row['maker_uid']) {
                if (isset($current_maker)) {
                    $makers[] = $current_maker;
                }
                $maker_vals = array(
                    'uid'=>$row['maker_uid'],
                    'slug'=>$row['maker_slug'],
                    'avatar_url'=>$row['maker_avatar_url'],
                    'name'=>$row['maker_name']
                );
                $current_maker = new Maker(array_merge($maker_vals, $defaults));
                $current_maker->products = array();
            }
            if (isset($row['product_uid'])) {
                $product_vals = array(
                    'uid'=>$row['product_uid'],
                    'slug'=>$row['product_slug'],
                    'avatar_url'=>$row['product_avatar_url'],
                    'name'=>$row['product_name']
                );
                if (count($current_maker->products) < $projects_per_speaker) {
                    $current_maker->products[] = new Product(array_merge($product_vals, $defaults));
                }
            }
        }
        $makers[] = $current_maker;
        return $makers;
    }

    public function getMakersWhoAreFriends(User $user) {
        $q = <<<EOD
SELECT m.* FROM makers m
INNER JOIN network_friends nf ON m.autofill_network_id = nf.friend_id
WHERE nf.user_id = :twitter_user_id AND nf.network = 'twitter' AND m.autofill_network = 'twitter'
AND m.creation_time >= :since_time
EOD;
        $vars = array (
            ':twitter_user_id' => $user->twitter_user_id,
            ':since_time' => $user->last_loaded_friends
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsObjects($ps, 'Maker');
    }
}
