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
    /**
     * @var bool Whether user has added a maker.
     */
    var $has_added_maker = false;
    /**
     * @var bool Whether user has added a product.
     */
    var $has_added_product = false;
    /**
     * @var bool Whether user has added a role.
     */
    var $has_added_role = false;
    /**
     * @var str Last network friends fetch time.
     */
    var $last_loaded_friends;
    /**
     * @var bool Whether or not user gets maker change email notifications.
     */
    var $is_subscribed_maker_change_email = true;
    /**
     * @var str Last time an email notification of a maker change was sent.
     */
    var $last_maker_change_email_sent;
    /**
     * @var bool Whether or not user should get friend activity email notifications.
     */
    var $is_subscribed_friend_activity_email = true;
    /**
     * @var bool Whether or not user should get Makerbase announcements via email.
     */
    var $is_subscribed_announcements_email = true;
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
            $this->has_added_maker = PDODAO::convertDBToBool($row['has_added_maker']);
            $this->has_added_product = PDODAO::convertDBToBool($row['has_added_product']);
            $this->has_added_role = PDODAO::convertDBToBool($row['has_added_role']);
            $this->last_loaded_friends = $row['last_loaded_friends'];
            $this->is_subscribed_maker_change_email = PDODAO::convertDBToBool($row['is_subscribed_maker_change_email']);
            $this->last_maker_change_email_sent = $row['last_maker_change_email_sent'];
            $this->is_subscribed_friend_activity_email =
                PDODAO::convertDBToBool($row['is_subscribed_friend_activity_email']);
            $this->is_subscribed_announcements_email =
                PDODAO::convertDBToBool($row['is_subscribed_announcements_email']);
        }
    }
}