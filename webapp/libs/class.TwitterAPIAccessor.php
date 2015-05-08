<?php

class TwitterAPIAccessor {
    /**
     * A list of Twitter API error codes and their explanations as per
     * http://dev.twitter.com/pages/responses_errors
     * @var array
     */
    private $error_codes = array(
         '304' => 'There was no new data to return.',
         '400' => 'The request was invalid.',
         '401' => 'Authentication credentials were missing or incorrect.',
         '403' => 'The request is understood, but it has been refused.',
         '404' => 'The URI requested is invalid or the resource requested, such as a user, does not exist.',
         '406' => 'Invalid format specified in the request.',
         '420' => 'You are being rate limited.',
         '429' => 'Too many requests.',
         '500' => 'Something is broken on Twitter\'s end.',
         '502' => 'Twitter is down or being upgraded.',
         '503' => 'The Twitter servers are up, but overloaded with requests. Try again later.' );

    /**
     * Verify OAuth Twitter credentials.
     * @param TwitterOAuth
     * @return mixed null if not authorized; array of user data if authorized
     * @throws Exception if HTTP status code from Twitter is not 200 OK
     */
    public function verifyCredentials(TwitterOAuth $toa) {
        $endpoint = 'account/verify_credentials';
        $args = array('include_entities'=>'false', 'skip_status'=>'true');

        $payload = $toa->OAuthRequest($endpoint, 'GET', $args);
        $http_status = $toa->lastStatusCode();

        if ($http_status == 200) {
            $user = $this->parseJSONUser($payload);
            return $user;
        } else {
            throw new APIErrorException(self::translateErrorCode($http_status, true));
        }
    }
    /**
     * Get Twitter user by username.
     * @param  str       $twitter_username
     * @param  TwitterOAuth $toa
     * @return array
     */
    public function getUser($twitter_username, TwitterOAuth $toa) {
        $endpoint = 'users/show/'.$twitter_username;
        $payload = $toa->OAuthRequest($endpoint, 'GET', null);
        $http_status = $toa->lastStatusCode();
        // echo '<pre>';
        // print_r($payload);
        // echo '</pre>';
        if ($http_status == 200) {
            $user = $this->parseJSONUser($payload);
            return $user;
        } else {
            throw new APIErrorException(self::translateErrorCode($http_status, true));
        }
    }
    /**
     * Get 5,000 follower IDs for a Twitter user.
     * @param  str $twitter_user_id
     * @param  TwitterOAuth $toa
     * @return array
     * @throws APIErrorException
     */
    public function get5KFollowers($twitter_user_id, TwitterOAuth $toa) {
        $endpoint = 'followers/ids';
        $payload = $toa->OAuthRequest($endpoint, 'GET', array('stringify_ids'=>true, 'count'=>5000,
            'user_id'=>$twitter_user_id));
        $http_status = $toa->lastStatusCode();
        // echo '<pre>';
        // print_r($payload);
        // echo '</pre>';

        if ($http_status == 200) {
            $follower_ids = JSONDecoder::decode($payload);
            return $follower_ids;
        } else {
            throw new APIErrorException(self::translateErrorCode($http_status, true));
        }
    }
    /**
     * Get Twitter user by username.
     * @param  str       $search_term
     * @param  TwitterOAuth $toa
     * @return array
     */
    public function searchUsers($search_term, TwitterOAuth $toa) {
        $endpoint = 'users/search';
        $payload = $toa->OAuthRequest($endpoint, 'GET', array('q'=>$search_term, 'count'=>3));
        $http_status = $toa->lastStatusCode();
        // echo '<pre>';
        // print_r($payload);
        // echo '</pre>';
        if ($http_status == 200) {
            $users = $this->parseJSONUsers($payload);
            return $users;
        } else {
            throw new APIErrorException(self::translateErrorCode($http_status, true));
        }
    }
    /**
     * Parse JSON list of users
     * @param str $data JSON user info.
     * @return array user data
     */
    public function parseJSONUsers($data) {
        $json = JSONDecoder::decode($data);
        $parsed_payload = array();
        //print_r($json);

        //If it's a list of users, set the cursor
        if (isset($json->users)) {
            if (isset($json->next_cursor)) {
                $this->next_cursor =  $json->next_cursor_str;
            }
            foreach ($json->users as $user) {
                $parsed_payload[] = self::convertJSONtoUserArray($user);
            }
        } else {
            foreach ($json as $user) {
                $parsed_payload[] = self::convertJSONtoUserArray($user);
            }
        }
        return $parsed_payload;
    }
    /**
     * Parse user JSON.
     * @param str $data JSON user info.
     * @return array user data
     */
    public function parseJSONUser($data) {
        $json = JSONDecoder::decode($data);
        //print_r($json);
        if (isset($json)) {
            return self::convertJSONtoUserArray($json);
        }
        return null;
    }
    /**
     * Convert JSON representation of a user to an array.
     * @param str $json_user
     * @return array User values
     */
    private function convertJSONtoUserArray($json_user) {
        $result = array(
            'user_id'         => (string)$json_user->id_str,
            'user_name'       => (string)$json_user->screen_name,
            'full_name'       => (string)$json_user->name,
            'avatar'          => str_replace('_normal', '', (string)$json_user->profile_image_url),
            'location'        => (string)$json_user->location,
            'description'     => (string)$json_user->description,
            'url'             => (isset($json_user->entities->url->urls[0]->expanded_url))
                                    ?(string)$json_user->entities->url->urls[0]->expanded_url
                                    :null,
            'is_verified'     => (integer)self::boolToInt($json_user->verified),
            'is_protected'    => (integer)self::boolToInt($json_user->protected),
            'follower_count'  => (integer)$json_user->followers_count,
            'friend_count'    => (integer)$json_user->friends_count,
            'post_count'      => (integer)$json_user->statuses_count,
            'favorites_count' => (integer)$json_user->favourites_count,
            'joined'          => gmdate("Y-m-d H:i:s", strToTime($json_user->created_at)),
            'network'         => 'twitter'
        );
        return $result;
    }
    /**
     * Convert a var whose value is true to 1, else 0.
     * @param bool $bool_val
     * @return int 1 or 0
     */
    private static function boolToInt($bool_val) {
        return ($bool_val) ?1:0;
    }
    /**
     * Translates a Twitter API code to its corresponding explanation, as described in this link:
     * http://dev.twitter.com/pages/responses_errors
     *
     * @param str $error_code The error code.
     * @param bool $include_code Whether or not to include the code in the output.
     * @return str Translated error code
     */
    public function translateErrorCode($error_code, $include_code = true) {
        $translation = '';
        $error_code = strval($error_code);
        if (array_key_exists($error_code, $this->error_codes)) {
            $translation = $this->error_codes[$error_code];
        }
        // if the $include_code flag is set, append the error code to the explanation
        if ($include_code) {
            $translation = 'Twitter API response '.$error_code . ': ' . $translation;
        }
        return $translation;
    }
}