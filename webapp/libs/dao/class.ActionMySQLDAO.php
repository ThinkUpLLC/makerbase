<?php
class ActionMySQLDAO extends PDODAO {

    public function insert(Action $action) {
        $q = <<<EOD
INSERT INTO actions (
user_id, ip_address, action_type, severity, object_id, object_type, object2_id, object2_type, metadata
) VALUES (
:user_id, :ip_address, :action_type, :severity, :object_id, :object_type, :object2_id, :object2_type, :metadata
)
EOD;
        $vars = array (
            ':user_id' => $action->user_id,
            ':ip_address' => $action->ip_address,
            ':action_type' => $action->action_type,
            ':severity' => $action->severity,
            ':object_id' => $action->object_id,
            ':object_type' => $action->object_type,
            ':object2_id' => $action->object2_id,
            ':object2_type' => $action->object2_type,
            ':metadata' => $action->metadata,
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getInsertId($ps);
    }

    public function getUserConnectionsActivities($user_id) {
        $q = <<<EOD
SELECT a.*, u.name, u.twitter_user_id FROM actions a INNER JOIN connections c
ON c.object_type = a.object_type and c.object_id = a.object_id
INNER JOIN users u ON a.user_id = u.id
WHERE c.user_id = :user_id ORDER BY time_performed DESC LIMIT 7;
EOD;
        $vars = array (
            ':user_id' => $user_id
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }

    public function getUserActivities($user_id) {
        $q = <<<EOD
SELECT a.*, u.name, u.twitter_user_id FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE a.user_id = :user_id ORDER BY time_performed DESC;
EOD;
        $vars = array (
            ':user_id' => $user_id
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }

    public function getActivitiesPerformedOnMaker(Maker $maker) {
        $q = <<<EOD
SELECT a.*, u.name, u.twitter_user_id FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE a.object_type = 'Maker' AND a.object_id = :maker_id ORDER BY time_performed DESC;
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

    public function getActivitiesPerformedOnProduct(Product $product) {
        $q = <<<EOD
SELECT a.*, u.name, u.twitter_user_id FROM actions a
INNER JOIN users u ON a.user_id = u.id
WHERE a.object_type = 'Product' AND a.object_id = :product_id ORDER BY time_performed DESC;
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

    public function getActivities() {
        $q = <<<EOD
SELECT a.*, u.name, u.twitter_user_id FROM actions a
INNER JOIN users u ON a.user_id = u.id
ORDER BY time_performed DESC LIMIT 7;
EOD;
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q);
        $actions = $this->getDataRowsAsObjects($ps, 'Action');
        foreach ($actions as $action) {
            $action->metadata = JSONDecoder::decode($action->metadata);
        }
        return $actions;
    }
}
