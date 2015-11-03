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

    public function deleteByMaker($maker) {
        $q = <<<EOD
DELETE FROM inspirations WHERE maker_id = :maker_id OR inspirer_maker_id = :maker_id
EOD;
        $vars = array (
            ':maker_id' => $maker->id
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
ORDER BY creation_time DESC
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
SELECT m.*, i.uid AS inspiration_uid, i.description AS inspiration_description FROM inspirations i
INNER JOIN makers m ON i.maker_id = m.id
WHERE m.is_archived = 0 AND i.inspirer_maker_id = :maker_id  AND i.is_shown_on_inspirer = 1
ORDER BY creation_time DESC
EOD;
        $vars = array (
            ':maker_id' => $maker->id,
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsObjects($ps, "Maker");
    }

    public function getTotal() {
        $q = <<<EOD
SELECT count(*) AS total FROM inspirations;
EOD;
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q);
        $result = $this->getDataRowAsArray($ps);
        return $result['total'];
    }

    public function getTrendingInspirations($limit = 4) {
        $q = <<<EOD
SELECT m.* FROM (
    SELECT COUNT( * ) AS total_inspirations, inspirer_maker_id, creation_time
    FROM inspirations
    GROUP BY inspirer_maker_id
    ORDER BY total_inspirations DESC
    LIMIT 10
) AS total_inspirations
INNER JOIN makers m ON total_inspirations.inspirer_maker_id = m.id
WHERE total_inspirations > 1
ORDER BY total_inspirations.creation_time DESC
LIMIT :limit;
EOD;

        $vars = array (
            ':limit' => $limit,
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $inspirer_makers = $this->getDataRowsAsObjects($ps, 'Maker');
        $trending_inspirations = array();

        foreach ($inspirer_makers as $inspirer_maker) {
            $inspires = $this->getInspiredMakers($inspirer_maker);
            $inspirer_maker->inspires = array_slice($inspires, 0, 4);
            $trending_inspirations[] = $inspirer_maker;
        }
        return $trending_inspirations;
    }

    public function getNewestInspirations($limit = 4) {
        $q = <<<EOD
SELECT inspirers.*, m1.uid AS inspired_maker_uid, m1.slug AS inspired_maker_slug,
m1.avatar_url AS inspired_maker_avatar_url, m1.name AS inspired_maker_name
FROM (
    SELECT MAX(i.id), i.maker_id, i.maker_id AS inspiration_maker_id, i.inspirer_maker_id, m.uid AS inspirer_maker_uid,
    m.slug AS inspirer_maker_slug,
    m.avatar_url AS inspirer_maker_avatar_url, m.name AS inspirer_maker_name,
    i.description AS inspiration_description, i.creation_time AS inspiration_creation_time, i.id AS inspiration_id,
    i.uid AS inspiration_uid
    FROM  inspirations i
    INNER JOIN makers m ON inspirer_maker_id = m.id
    WHERE i.description !=  '' AND is_shown_on_inspirer = 1
    GROUP BY i.inspirer_maker_id
    ORDER BY i.creation_time DESC
    LIMIT :limit
    ) AS inspirers
INNER JOIN makers m1 ON inspirers.maker_id = m1.id
EOD;

        $vars = array (
            ':limit' => $limit,
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);

        $maker_defaults = array('id'=>null, 'creation_time'=>null, 'url'=>null, 'is_archived'=>0, 'is_frozen'=>0,
            'autofill_network_id'=>null, 'autofill_network'=>null, 'autofill_network_username'=>null,
            'description'=>'');

        $new_inspirations = array();

        foreach ($rows as $row) {
            $inspirer_maker_vals = array(
                'uid'=>$row['inspirer_maker_uid'],
                'slug'=>$row['inspirer_maker_slug'],
                'avatar_url'=>$row['inspirer_maker_avatar_url'],
                'name'=>$row['inspirer_maker_name']
            );
            $inspired_maker_vals = array(
                'uid'=>$row['inspired_maker_uid'],
                'slug'=>$row['inspired_maker_slug'],
                'avatar_url'=>$row['inspired_maker_avatar_url'],
                'name'=>$row['inspired_maker_name']
            );
            $inspiration_vals = array(
                'id' => $row['inspiration_id'],
                'uid' => $row['inspiration_uid'],
                'maker_id' => $row['inspiration_maker_id'],
                'inspirer_maker_id' => $row['inspirer_maker_id'],
                'description' => $row['inspiration_description'],
                'is_shown_on_inspirer' => 1,
                'creation_time' => $row['inspiration_creation_time']
            );

            $current_inspirer_maker = new Maker(array_merge($inspirer_maker_vals, $maker_defaults));
            $current_inspirer_maker->inspiration = new Inspiration($inspiration_vals);
            $inspired_maker = new Maker(array_merge($inspired_maker_vals, $maker_defaults) );
            $current_inspirer_maker->inspiration->inspired_maker = $inspired_maker;

            $new_inspirations[] = $current_inspirer_maker;
        }
        return $new_inspirations;
    }
}
