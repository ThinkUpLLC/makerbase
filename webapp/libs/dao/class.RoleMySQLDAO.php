<?php
class RoleMySQLDAO extends MakerbasePDODAO {

    public function archive($uid) {
        return $this->setIsArchived($uid, true);
    }

    public function unarchive($uid) {
        return $this->setIsArchived($uid, false);
    }

    private function setIsArchived($uid, $is_archived) {
        $q = <<<EOD
UPDATE roles SET is_archived = :is_archived WHERE uid = :uid
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

    public function getByMaker($maker_id) {
        $q = <<<EOD
SELECT r.*, r.id AS role_id, r.uid AS role_uid, p.*, p.id AS product_id, p.uid AS product_uid,
r.is_archived AS role_is_archived FROM roles r
INNER JOIN products p ON r.product_id = p.id WHERE r.is_archived = 0 AND maker_id = :maker_id
ORDER BY ISNULL(start), -ISNULL(end), start DESC
EOD;
        $vars = array ( ':maker_id' => $maker_id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);
        $roles = array();
        foreach ($rows as $row) {
            $role = new Role($row);
            $role->id = $row['role_id'];
            $role->uid = $row['role_uid'];
            $role->is_archived = PDODAO::convertDBToBool($row['role_is_archived']);
            $product = new Product($row);
            $product->id = $row['product_id'];
            $product->uid = $row['product_uid'];
            $role->product = $product;
            $roles[] = $role;
        }
        return $roles;
    }

    public function get($uid) {
        $q = "SELECT r.* FROM roles r WHERE uid = :uid";
        $vars = array ( ':uid' => $uid);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $role = $this->getDataRowAsObject($ps, 'Role');
        if (!isset($role)) {
            throw new RoleDoesNotExistException('Role '.$uid.' does not exist.');
        }
        return $role;
    }

    public function update(Role $role) {
        $q = "UPDATE roles SET start = :start, end = :end, role = :role WHERE id = :id";
        $vars = array (
            ':id' => (int)$role->id,
            ':start' => $role->start,
            ':end' => $role->end,
            ':role' => $role->role
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return ($this->getUpdateCount($ps) > 0);
    }

    public function getByProduct($product_id) {
        $q = <<<EOD
SELECT r.*, r.id AS role_id, r.uid AS role_uid, m.*, m.id AS maker_id, m.uid AS maker_uid,
r.is_archived AS role_is_archived FROM roles r
INNER JOIN makers m ON r.maker_id = m.id WHERE r.is_archived = 0 AND r.product_id = :product_id
ORDER BY ISNULL(start), start ASC
EOD;
        $vars = array ( ':product_id' => $product_id);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $rows = $this->getDataRowsAsArrays($ps);
        $roles = array();
        foreach ($rows as $row) {
            $role = new Role($row);
            $role->id = $row['role_id'];
            $role->uid = $row['role_uid'];
            $role->is_archived = PDODAO::convertDBToBool($row['role_is_archived']);
            $maker = new Maker($row);
            $maker->id = $row['maker_id'];
            $maker->uid = $row['maker_uid'];
            $role->maker = $maker;
            $roles[] = $role;
        }
        return $roles;
    }

    public function getFrequentCollaborators($maker_id, $limit) {
        $q = <<<EOD
SELECT maker_id, total_collaborations, m.name, m.uid, m.avatar_url, m.slug FROM
(
    SELECT COUNT(DISTINCT maker_id, product_id) AS total_collaborations, maker_id FROM roles
    WHERE is_archived = 0 AND maker_id != :maker_id AND product_id IN
    (
        SELECT product_id FROM roles WHERE maker_id = :maker_id AND is_archived = 0
    ) GROUP BY maker_id
) S
INNER JOIN makers m ON maker_id = m.id
WHERE total_collaborations > 1
ORDER BY total_collaborations DESC
LIMIT :limit ;
EOD;
        $vars = array (
            ':maker_id' => $maker_id,
            ':limit' => $limit
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsArrays($ps);
    }

    public function getCommonProjects($maker_id, $collaborator_id) {
        $q = <<<EOD
SELECT DISTINCT(p.id), p.* FROM products p INNER JOIN roles r ON r.product_id = p.id WHERE r.maker_id = :maker_id
AND p.is_archived = 0 AND product_id IN (
    SELECT product_id FROM roles r WHERE maker_id = :collaborator_id AND r.is_archived = 0
)
EOD;
        $vars = array (
            ':maker_id' => $maker_id,
            ':collaborator_id' => $collaborator_id
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getDataRowsAsObjects($ps, 'Product');
    }

    public function insert(Role $role) {
        $role->uid = self::generateRandomString(6);
        $q = <<<EOD
INSERT INTO roles (
uid, maker_id, product_id, start, end, role
) VALUES (
:uid, :maker_id, :product_id, :start, :end, :role
)
EOD;
        $vars = array (
            ':uid' => $role->uid,
            ':maker_id' => $role->maker_id,
            ':product_id' => $role->product_id,
            ':start' => $role->start,
            ':end' => $role->end,
            ':role' => $role->role
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $try_insert = true;
        while ($try_insert) {
            try {
                $ps = $this->execute($q, $vars);
                $try_insert = false;
                $role->id = $this->getInsertId($ps);
                return $role;
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
}
