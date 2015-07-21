<?php
class User {
    /**
     * @var int Internal unique ID.
     */
    var $id;
    /**
     * @var str External unique ID.
     */
    var $uid;
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
     * @var str Creation time.
     */
    var $creation_time;
    /**
     * @var str Last login time.
     */
    var $last_login_time;
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
    /**
     * @var bool Whether or not object is frozen (locked from changes).
     */
    var $is_frozen = false;
    /**
     * @var str User email address.
     */
    var $email;
    /**
     * @var int Email verification code.
     */
    var $email_verification_code;
    /**
     * @var bool Whether or not email is verified.
     */
    var $is_email_verified = false;
    public function __construct($row = false) {
        if ($row) {
            $this->id = $row['id'];
            $this->uid = $row['uid'];
            $this->name = $row['name'];
            $this->url = $row['url'];
            $this->avatar_url = $row['avatar_url'];
            $this->twitter_user_id = $row['twitter_user_id'];
            $this->twitter_username = $row['twitter_username'];
            $this->creation_time = $row['creation_time'];
            $this->last_login_time = $row['last_login_time'];
            $this->twitter_oauth_access_token = $row['twitter_oauth_access_token'];
            $this->twitter_oauth_access_token_secret = $row['twitter_oauth_access_token_secret'];
            $this->maker_id = $row['maker_id'];
            $this->is_frozen = PDODAO::convertDBToBool($row['is_frozen']);
            $this->email = $row['email'];
            $this->email_verification_code = $row['email_verification_code'];
            $this->is_email_verified = PDODAO::convertDBToBool($row['is_email_verified']);
        }
    }
}
