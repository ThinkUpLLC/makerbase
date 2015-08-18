<?php

class Maker {
    /**
     * @var int Internal unique ID.
     */
    var $id;
    /**
     * @var str External unique ID.
     */
    var $uid;
    /**
     * @var str URL slug.
     */
    var $slug;
    /**
     * @var str Full name.
     */
    var $name;
    /**
     * @var str Web site URL.
     */
    var $url;
    /**
     * @var str Avatar URL.
     */
    var $avatar_url;
    /**
     * @var str Creation time.
     */
    var $creation_time;
    /**
     * @var bool Has maker been archived, 1 yes, 0 no.
     */
    var $is_archived = false;
    /**
     * @var bool Whether or not object is frozen (locked from changes).
     */
    var $is_frozen = false;
    /**
     * @var str Network ID of the maker from autofill.
     */
    var $autofill_network_id;
    /**
     * @var str Source network of the autofill.
     */
    var $autofill_network;
    /**
     * @var str Network username of the maker from autofill.
     */
    var $autofill_network_username;
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->uid = $row['uid'];
            $this->slug = $row['slug'];
            $this->name = $row['name'];
            $this->url = $row['url'];
            $this->avatar_url = $row['avatar_url'];
            $this->creation_time = $row['creation_time'];
            $this->is_archived = PDODAO::convertDBToBool($row['is_archived']);
            $this->is_frozen = PDODAO::convertDBToBool($row['is_frozen']);
            $this->autofill_network_id = $row['autofill_network_id'];
            $this->autofill_network = $row['autofill_network'];
            $this->autofill_network_username = $row['autofill_network_username'];
        }
    }
}