<?php

class Action {
    /**
     * @var int Minor severity
     */
    const SEVERITY_MINOR = 0;
    /**
     * @var int Minor severity
     */
    const SEVERITY_NORMAL = 1;
    /**
     * @var int Minor severity
     */
    const SEVERITY_MAJOR = 2;
    /**
     * @var int Internal unique ID.
     */
    var $id;
    /**
     * @var str Time action was performed.
     */
    var $time_performed;
    /**
     * @var int ID of user who performed action.
     */
    var $user_id;
    /**
     * @var str IP address of client where action was performed.
     */
    var $ip_address;
    /**
     * @var str Type of action (create, update, archive, etc).
     */
    var $action_type;
    /**
     * @var int Action severity (higher more severe).
     */
    var $severity;
    /**
     * @var int ID of affected object.
     */
    var $object_id;
    /**
     * @var str Type of affected object (maker, role, product).
     */
    var $object_type;
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->time_performed = $row['time_performed'];
            $this->user_id = $row['user_id'];
            $this->ip_address = $row['ip_address'];
            $this->action_type = $row['action_type'];
            $this->severity = $row['severity'];
            $this->object_id = $row['object_id'];
            $this->object_type = $row['object_type'];
        }
    }
}
