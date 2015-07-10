<?php
class Connection {
    /**
     * @var int User ID.
     */
    var $user_id;
    /**
     * @var int Product, role, maker ID.
     */
    var $object_id;
    /**
     * @var str Type of affected object (maker, role, product).
     */
    var $object_type;
    /**
     * @var str Creation time of the connection.
     */
    var $creation_time;
    public function __construct($row = false) {
        if ($row) {
            $this->user_id = $row['user_id'];
            $this->object_id = $row['object_id'];
            $this->object_type = $row['object_type'];
            $this->creation_time = $row['creation_time'];
        }
    }
}