<?php

class Role {
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
     * @var bool Has role been archived, 1 yes, 0 no.
     */
    var $is_archived = false;
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->uid = $row['uid'];
            $this->product_id = $row['product_id'];
            $this->maker_id = $row['maker_id'];
            $this->role = $row['role'];
            $this->start = $row['start'];
            if (isset($this->start)) {
                $this->start_MY = date_format(date_create($this->start), 'M Y');
                $this->start_YM = date_format(date_create($this->start), 'Y-m');
                $this->start_m = date_format(date_create($this->start), 'm');
                $this->start_Y = date_format(date_create($this->start), 'Y');
            } else {
                $this->start_MY = null;
                $this->start_YM = null;
                $this->start_m = null;
                $this->start_Y = null;
            }
            $this->end = $row['end'];
            if (isset($this->end)) {
                $this->years = date_format(date_create($this->end), 'Y')
                    - date_format(date_create($this->start), 'Y');
                $this->end_MY = date_format(date_create($this->end), 'M Y');
                $this->end_YM = date_format(date_create($this->end), 'Y-m');
                $this->end_m = date_format(date_create($this->end), 'm');
                $this->end_Y = date_format(date_create($this->end), 'Y');
            } else {
                $this->end_MY = null;
                $this->end_YM = null;
                $this->end_m = null;
                $this->end_Y = null;
            }
            $this->end = $row['end'];
            $this->creation_time = $row['creation_time'];
            $this->is_archived = PDODAO::convertDBToBool($row['is_archived']);
        }
    }

    public static function getRandoPlaceholder() {
        $placeholders = array(
            "Herded cats",
            "Designed the logo",
            "Raised the barn",
            "Cranked widgets",
            "Brainstormed ideas",
            "Trained interns",
            "Suggested unicorns",
            "Managed community",
            "Brought donuts",
            "Wrote documentation",
            "Helped users",
            "Fixed bugs"
        );

        return $placeholders[rand()%count($placeholders)];
    }
}