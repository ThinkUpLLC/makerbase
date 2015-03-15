<?php

class Product {
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
     * @var str Product name.
     */
    var $name;
    /**
     * @var text Product description.
     */
    var $description;
    /**
     * @var str Product web site url.
     */
    var $url;
    /**
     * @var str Product avatar url.
     */
    var $avatar_url;
    /**
     * @var str Creation time.
     */
    var $creation_time;
    /**
     * @var bool Has product been archived, 1 yes, 0 no.
     */
    var $is_archived = false;
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->uid = $row['uid'];
            $this->slug = $row['slug'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->url = $row['url'];
            $this->avatar_url = $row['avatar_url'];
            $this->creation_time = $row['creation_time'];
            $this->is_archived = PDODAO::convertDBToBool($row['is_archived']);
        }
    }
}