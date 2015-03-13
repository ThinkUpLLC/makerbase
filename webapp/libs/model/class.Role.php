<?php

class Role {
    /**
     * @var int Internal unique ID.
     */
    var $id;
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
    /**
     * @var str Creation time.
     */
    var $creation_time;
    /**
     * @var bool Has role been archived
     */
    var $is_archived;
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->product_id = $row['product_id'];
            $this->maker_id = $row['maker_id'];
            $this->role = $row['role'];
            $this->start = $row['start'];
            if (isset($this->start)) {
                $this->start_MY = date_format(date_create($this->start), 'M Y');
                $this->start_YM = date_format(date_create($this->start), 'Y-m');
            }
            $this->end = $row['end'];
            if (isset($this->end)) {
                $this->years = date_format(date_create($this->end), 'Y')
                    - date_format(date_create($this->start), 'Y');
                $this->end_MY = date_format(date_create($this->end), 'M Y');
                $this->end_YM = date_format(date_create($this->end), 'Y-m');
            }
            $this->end = $row['end'];
            $this->creation_time = $row['creation_time'];
            $this->is_archived = PDODAO::convertDBToBool($row['is_archived']);
        }
    }
}