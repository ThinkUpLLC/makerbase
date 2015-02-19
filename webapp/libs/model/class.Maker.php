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
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->slug = $row['slug'];
            $this->username = $row['username'];
            $this->name = $row['name'];
            $this->url = $row['url'];
            $this->avatar_url = $row['avatar_url'];
        }
    }
}