<?php

class Role {
    /**
     * @var int Product ID.
     */
    var $product_id;
    /**
     * @var int Maker ID.
     */
    var $maker_id;
    /**
     * @var str Role of maker in product.
     */
    var $role;
    /**
     * @var date Start date.
     */
    var $start;
    /**
     * @var date End date.
     */
    var $end;
    public function __construct($row = false) {
        if ($row) {
            $this->product_id = $row['product_id'];
            $this->maker_id = $row['maker_id'];
            $this->role = $row['role'];
            $this->start = $row['start'];
            $this->end = $row['end'];
        }
    }
}