<?php
class ActionMySQLDAO extends MakerbasePDODAO {

    public function get($uid) {
        $q = <<<EOD
SELECT a.*, u.name, u.uid AS user_uid, u.twitter_username as username FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE a.uid = :uid AND is_admin = 0;
EOD;
        $vars = array ( ':uid' => $uid);
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        $action = $this->getDataRowAsObject($ps, "Action");
        if (!isset($action)) {
            throw new ActionDoesNotExistException('Action '.$uid.' does not exist.');
        } else {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $action;
    }

    public function insert(Action $action) {
        $action->uid = self::generateRandomString(6);
        $q = <<<EOD
INSERT INTO actions (
uid, user_id, ip_address, action_type, severity, object_id, object_type, object2_id, object2_type, metadata, is_admin
) VALUES (
:uid, :user_id, :ip_address, :action_type, :severity, :object_id, :object_type, :object2_id, :object2_type, :metadata,
:is_admin
)
EOD;
        $vars = array (
            ':uid' => $action->uid,
            ':user_id' => $action->user_id,
            ':ip_address' => $action->ip_address,
            ':action_type' => $action->action_type,
            ':severity' => $action->severity,
            ':object_id' => $action->object_id,
            ':object_type' => $action->object_type,
            ':object2_id' => $action->object2_id,
            ':object2_type' => $action->object2_type,
            ':metadata' => $action->metadata,
            ':is_admin' => PDODAO::convertBoolToDB($action->is_admin)
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $try_insert = true;
        while ($try_insert) {
            try {
                $ps = $this->execute($q, $vars);
                $try_insert = false;
                $action->id = $this->getInsertId($ps);
                self::insertActionObject($action->id, $action->user_id, 'User');
                self::insertActionObject($action->id, $action->object_id, $action->object_type);
                if (isset($action->object2_id) && isset($action->object2_type)) {
                    self::insertActionObject($action->id, $action->object2_id, $action->object2_type);
                }
                return $action;
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

    private function insertActionObject($action_id, $object_id, $object_type) {
        $q = <<<EOD
INSERT INTO action_objects (
action_id, object_id, object_type
) VALUES (
:action_id, :object_id, :object_type
)
EOD;
        $vars = array (
            ':action_id' => $action_id,
            ':object_id' => $object_id,
            ':object_type' => $object_type
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $this->execute($q, $vars);
    }

    public function getUserConnectionsActivities($user_id, $page=1, $limit=10) {
        $start = $limit * ($page - 1);
        $limit++;
        $q = <<<EOD
SELECT DISTINCT a.*, uactor.name, uactor.uid AS user_uid, uactor.twitter_username as username FROM actions a
INNER JOIN action_objects ao ON a.id = ao.action_id
INNER JOIN connections c ON c.object_type = ao.object_type and c.object_id = ao.object_id
INNER JOIN users uactor ON a.user_id = uactor.id
WHERE c.user_id = :user_id AND is_admin = 0
ORDER BY a.id DESC LIMIT :start, :limit;
EOD;
        $vars = array (
            ':user_id' => $user_id,
            ':start' => $start,
            ':limit' => $limit
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }

    public function getUserActivities($user_id, $page=1, $limit=20) {
        $start = $limit * ($page - 1);
        $limit++;
        $q = <<<EOD
SELECT a.*, u.name, u.uid AS user_uid, u.twitter_username as username FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE a.user_id = :user_id AND is_admin = 0
ORDER BY id DESC LIMIT :start, :limit;
EOD;
        $vars = array (
            ':user_id' => $user_id,
            ':start' => $start,
            ':limit' => $limit
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }

    public function getActivitiesPerformedOnMaker(Maker $maker, $page=1, $limit=10) {
        $start = $limit * ($page - 1);
        $limit++;
        $q = <<<EOD
SELECT a.*, u.name, u.uid AS user_uid, u.twitter_username as username FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE ((a.object_type = 'Maker' AND a.object_id = :maker_id)
OR (a.object2_type = 'Maker' AND a.object2_id = :maker_id))
AND is_admin = 0
ORDER BY id DESC LIMIT :start, :limit;
EOD;
        $vars = array (
            ':maker_id' => $maker->id,
            ':start' => $start,
            ':limit' => $limit
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }

    public function getLastAdminActivityPerformedOnMaker(Maker $maker) {
        $q = <<<EOD
SELECT a.*, u.name, u.uid AS user_uid, u.twitter_username as username FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE is_admin = 1 AND
((a.object_type = 'Maker' AND a.object_id = :maker_id) OR (a.object2_type = 'Maker' AND a.object2_id = :maker_id))
ORDER BY id DESC LIMIT 1;
EOD;
        $vars = array (
            ':maker_id' => $maker->id
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }

    public function getLastAdminActivityPerformedOnUser(User $user) {
        $q = <<<EOD
SELECT a.*, u.name, u.uid AS user_uid, u.twitter_username as username FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE a.object_type = 'User' AND a.object_id = :user_id AND is_admin = 1
ORDER BY id DESC LIMIT 1;
EOD;
        $vars = array (
            ':user_id' => $user->id
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }

    public function getActivitiesPerformedOnProduct(Product $product, $page=1, $limit=10) {
        $start = $limit * ($page - 1);
        $limit++;
        $q = <<<EOD
SELECT a.*, u.name, u.uid AS user_uid, u.twitter_username as username FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE ((a.object_type = 'Product' AND a.object_id = :product_id)
OR (a.object2_type = 'Product' AND a.object2_id = :product_id)) AND is_admin = 0
ORDER BY id DESC LIMIT :start, :limit;
EOD;
        $vars = array (
            ':product_id' => $product->id,
            ':start' => $start,
            ':limit' => $limit
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }

    public function getLastAdminActivityPerformedOnProduct(Product $product) {
        $q = <<<EOD
SELECT a.*, u.name, u.uid AS user_uid, u.twitter_username as username FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE ((a.object_type = 'Product' AND a.object_id = :product_id)
OR (a.object2_type = 'Product' AND a.object2_id = :product_id)) AND is_admin = 1
ORDER BY id DESC LIMIT 1;
EOD;
        $vars = array (
            ':product_id' => $product->id
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }

    public function getActivities($page = 1, $limit = 30) {
        $start = $limit * ($page - 1);
        $limit++;
        $q = <<<EOD
SELECT a.*, u.name, u.uid AS user_uid, u.twitter_username as username FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE is_admin = 0
ORDER BY id DESC LIMIT :start, :limit;
EOD;
        $vars = array (
            ':start' => $start,
            ':limit' => $limit
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }

    public function getAdminActivities($page = 1, $limit = 30) {
        $start = $limit * ($page - 1);
        $limit++;
        $q = <<<EOD
SELECT a.*, u.name, u.uid AS user_uid, u.twitter_username as username FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE is_admin = 1
ORDER BY id DESC LIMIT :start, :limit;
EOD;
        $vars = array (
            ':start' => $start,
            ':limit' => $limit
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }

    public function getTotal() {
        $q = <<<EOD
SELECT count(*) AS total FROM actions a WHERE is_admin = 0;
EOD;
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q);
        $result = $this->getDataRowAsArray($ps);
        return $result['total'];
    }

    public function deleteActionsForProduct($product_id) {
        return self::deleteActionsForObject('Product', $product_id);
    }

    public function deleteActionsForMaker($maker_id) {
        return self::deleteActionsForObject('Maker', $maker_id);
    }

    private function deleteActionsForObject($object_type, $object_id) {
        //Delete from actions where object is object 1
        $q = <<<EOD
DELETE FROM actions WHERE object_id = :object_id AND object_type = :object_type;
EOD;
        $vars = array (
            ':object_id' => $object_id,
            ':object_type' => $object_type
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $have_actions_been_deleted = ($this->getUpdateCount($ps) > 0);

        //Delete from actions where object is object 2
        $q = <<<EOD
DELETE FROM actions WHERE object2_id = :object2_id AND object2_type = :object2_type;
EOD;
        $vars = array (
            ':object2_id' => $object_id,
            ':object2_type' => $object_type
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $have_actions_been_deleted = $have_actions_been_deleted && ($this->getUpdateCount($ps) > 0);

        //Delete object from action_objects
        $q = <<<EOD
DELETE FROM action_objects WHERE object_id = :object_id AND object_type = :object_type;
EOD;
        $vars = array (
            ':object_id' => $object_id,
            ':object_type' => $object_type
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);

        return $have_actions_been_deleted && ($this->getUpdateCount($ps) > 0);
    }
}
