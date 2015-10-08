<?php
class InspirationMySQLDAO extends MakerbasePDODAO {

    public function insert(Inspiration $inspiration) {
        $inspiration->uid = self::generateRandomString(6);

        $q = <<<EOD
INSERT INTO inspirations (
uid, maker_id, inspirer_maker_id, description
) VALUES (
:uid, :maker_id, :inspirer_maker_id, :description
)
ON DUPLICATE KEY UPDATE description=:description;
EOD;
        $vars = array (
            ':uid' => $inspiration->uid,
            ':maker_id' => $inspiration->maker_id,
            ':inspirer_maker_id' => $inspiration->inspirer_maker_id,
            ':description' => $inspiration->description
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $try_insert = true;
        while ($try_insert) {
            try {
                $ps = $this->execute($q, $vars);
                $inspiration->id = $this->getInsertId($ps);
                return $inspiration;
            } catch (PDOException $e) {
                $message = $e->getMessage();
                if (strpos($message,'Duplicate entry') !== false) {
                    if (strpos($message, "for key 'uid'") !== false) {
                        $try_insert = true;
                    } elseif (strpos($message, "for key 'maker_id'") !== false) {
                        throw new DuplicateInspirationException($message);
                    } else {
                        throw new PDOException($message);
                    }
                } else {
                    throw new PDOException($message);
                }
            }
        }
    }

    public function delete($inspiration) {
        $q = <<<EOD
DELETE FROM inspirations WHERE uid = :uid
EOD;
        $vars = array (
            ':uid' => $inspiration->uid
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function edit($uid, $description) {
        $q = "UPDATE inspirations SET description = :description WHERE uid = :uid";
        $vars = array (
            ':description' => $description,
            ':uid' => $uid,
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function hide($uid) {
        $q = "UPDATE inspirations SET is_shown_on_inspirer = 0 WHERE uid = :uid";
        $vars = array (
            ':uid' => $uid,
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function get($uid) {
        $q = "SELECT i.* FROM inspirations i WHERE i.uid = :uid";
        $vars = array ( ':uid' => $uid);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $inspiration = $this->getDataRowAsObject($ps, 'Inspiration');
        if (!isset($inspiration)) {
            throw new InspirationDoesNotExistException('Inspiration '.$uid.' does not exist.');
        }
        return $inspiration;
    }

    public function getInspirers(Maker $maker) {
        $q = <<<EOD
SELECT m.*, i.uid as inspiration_uid, i.description FROM inspirations i
INNER JOIN makers m ON i.inspirer_maker_id = m.id
WHERE i.maker_id = :maker_id AND m.is_archived = 0
EOD;
        $vars = array (
            ':maker_id' => $maker->id,
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsObjects($ps, "Maker");
    }

    public function getInspiredMakers(Maker $maker) {
        $q = <<<EOD
SELECT m.*, i.uid AS inspiration_uid FROM inspirations i
INNER JOIN makers m ON i.maker_id = m.id
WHERE m.is_archived = 0 AND i.inspirer_maker_id = :maker_id  AND i.is_shown_on_inspirer = 1
EOD;
        $vars = array (
            ':maker_id' => $maker->id,
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsObjects($ps, "Maker");
    }
}
