<?php

class Inspiration {
    /**
     * @var int Internal unique ID.
     */
    var $id;
    /**
     * @var str External unique ID.
     */
    var $uid;
    /**
     * @var int Maker ID.
     */
    var $maker_id;
    /**
     * @var int Maker who inspired.
     */
    var $inspirer_maker_id;
    /**
     * @var str Inspiration description.
     */
    var $description;
    /**
     * @var bool Whether or not inspiration is shown on inspirer page.
     */
    var $is_shown_on_inspirer = false;
    /**
     * @var str Creation time of the inspiration.
     */
    var $creation_time;
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->uid = $row['uid'];
            $this->maker_id = $row['maker_id'];
            $this->inspirer_maker_id = $row['inspirer_maker_id'];
            $this->description = $row['description'];
            $this->is_shown_on_inspirer = PDODAO::convertDBToBool($row['is_shown_on_inspirer']);
            $this->creation_time = $row['creation_time'];
        }
    }

    public static function getRandoPlaceholder() {
        $placeholders = array(
            "Moved mountains",
            "Shared a powerful story",
            "Empowered other people",
            "Changed the world by doing important work",
            "Challenged me to get better",
            "Opened my eyes to a new way of seeing things",
            "Built an app that made my life easier",
            "Created a web site that taught me something",
            "Gave a talk that really made me think",
            "Clarified my thinking",
            "Set an example of someone I aspire to be",
            "Created something I love"
        );

        return $placeholders[rand()%count($placeholders)];
    }
}