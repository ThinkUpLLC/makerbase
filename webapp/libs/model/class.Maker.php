<?php

class Maker {
    /**
     * @var int Internal unique ID.
     */
    var $id;
    /**
     * @var str URL slug.
     */
    var $slug;
    /**
     * @var str Username.
     */
    var $username;
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
     * @var bool Has maker been archived
     */
    var $is_archived;
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->slug = $row['slug'];
            $this->username = $row['username'];
            $this->name = $row['name'];
            $this->url = $row['url'];
            $this->avatar_url = $row['avatar_url'];
            $this->creation_time = $row['creation_time'];
            $this->is_archived = PDODAO::convertDBToBool($row['is_archived']);
        }
    }
}