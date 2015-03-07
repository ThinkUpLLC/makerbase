<?php
class ActionMySQLDAO extends PDODAO {

    public function insert(Action $action) {
        $q = <<<EOD
INSERT INTO actions (
ip_address, action_type, severity, object_id, object_type
) VALUES (
:ip_address, :action_type, :severity, :object_id, :object_type
)
EOD;
        $vars = array (
            ':ip_address' => $action->ip_address,
            ':action_type' => $action->action_type,
            ':severity' => $action->severity,
            ':object_id' => $action->object_id,
            ':object_type' => $action->object_type
        );
        if ($this->profiler_enabled) { Profiler::setDAOMethod(__METHOD__); }
        //echo self::mergeSQLVars($q, $vars);
        $ps = $this->execute($q, $vars);
        return $this->getInsertId($ps);
    }
}
