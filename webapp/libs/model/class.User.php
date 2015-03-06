<?php
class User {
    /**
     * @var int Internal unique ID.
     */
    var $id;
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
     * @var str Twitter user ID.
     */
    var $twitter_user_id;
    /**
     * @var str Twitter username.
     */
    var $twitter_username;
    /**
     * @var str Last login time.
     */
    var $last_login;
    /**
     * @var str Twitter OAuth token.
     */
    var $twitter_oauth_access_token;
    /**
     * @var str Twitter OAuth secret.
     */
    var $twitter_oauth_access_token_secret;
    /**
     * @var int Maker ID if claimed.
     */
    var $maker_id;
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->url = $row['url'];
            $this->avatar_url = $row['avatar_url'];
            $this->twitter_user_id = $row['twitter_user_id'];
            $this->twitter_username = $row['twitter_username'];
            $this->last_login = $row['last_login'];
            $this->twitter_oauth_access_token = $row['twitter_oauth_access_token'];
            $this->twitter_oauth_access_token_secret = $row['twitter_oauth_access_token_secret'];
            $this->maker_id = $row['maker_id'];
        }
    }
}
