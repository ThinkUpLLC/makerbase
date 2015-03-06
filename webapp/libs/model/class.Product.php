<?php

class Product {
    /**
     * @var int Internal unique ID.
     */
    var $id;
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
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->slug = $row['slug'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->url = $row['url'];
            $this->avatar_url = $row['avatar_url'];
        }
    }
}