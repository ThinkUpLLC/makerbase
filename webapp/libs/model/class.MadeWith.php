<?php

class MadeWith {
    /**
     * @var int Internal unique ID.
     */
    var $id;
    /**
     * @var str External unique ID.
     */
    var $uid;
    /**
     * @var int Product ID.
     */
    var $product_id;
    /**
     * @var int Product ID used by product.
     */
    var $used_product_id;
    /**
     * @var str Creation time.
     */
    var $creation_time;
    /**
     * @var bool Has use been archived, 1 yes, 0 no.
     */
    var $is_archived = false;
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->uid = $row['uid'];
            $this->product_id = $row['product_id'];
            $this->used_product_id = $row['used_product_id'];
            $this->creation_time = $row['creation_time'];
            $this->is_archived = PDODAO::convertDBToBool($row['is_archived']);
        }
    }
}
