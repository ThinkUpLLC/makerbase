<?php
class ActionMySQLDAO extends PDODAO {

    public function insert(Action $action) {
        $q = <<<EOD
INSERT INTO actions (
user_id, ip_address, action_type, severity, object_id, object_type, object_slug, object_name
) VALUES (
:user_id, :ip_address, :action_type, :severity, :object_id, :object_type, :object_slug, :object_name
)
EOD;
        $vars = array (
            ':user_id' => $action->user_id,
            ':ip_address' => $action->ip_address,
            ':action_type' => $action->action_type,
            ':severity' => $action->severity,
            ':object_id' => $action->object_id,
            ':object_type' => $action->object_type,
            ':object_slug' => $action->object_slug,
            ':object_name' => $action->object_name
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        $ps = $this->execute($q, $vars);
        return $this->getInsertId($ps);
    }
}
