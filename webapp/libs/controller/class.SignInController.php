<?php

class SignInController extends Controller {
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
         '500' => 'Something is broken on Twitter\'s end.',
         '502' => 'Twitter is down or being upgraded.',
         '503' => 'The Twitter servers are up, but overloaded with requests. Try again later.' );

    public function control(){
        $controller = new LandingController(true);
        if (isset($_GET['oauth_token'])) {
            $this->disableCaching();

            $cfg = Config::getInstance();
            $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
            $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');
            $request_token = $_GET['oauth_token'];
            $request_token_secret = SessionCache::get('oauth_request_token_secret');

            $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret, $request_token,
                $request_token_secret);

            if (isset($_GET['oauth_verifier'])) {
                $token_array = $twitter_oauth->getAccessToken($_GET['oauth_verifier']);
            } else {
                $token_array = null;
            }

            if (isset($token_array['oauth_token']) && isset($token_array['oauth_token_secret'])) {
                $authed_twitter_user = $this->verifyCredentials($twitter_oauth);

                if ( isset($authed_twitter_user) && isset($authed_twitter_user['user_name'])
                    && isset($authed_twitter_user['user_id'])) {
                    // echo "Time to insert a new user!";
                    // echo "User ID: ". $authed_twitter_user['user_id']."<br>";
                    // echo "User name: ". $authed_twitter_user['user_name']."<br>";
                    //print_r($authed_twitter_user);
                    $user_dao = new UserMySQLDAO();
                    try {
                        $user = $user_dao->get($authed_twitter_user['user_id']);
                        $user_dao->updateLastLogin($user);
                    } catch (UserDoesNotExistException $e) {
                        $user = new User();
                        $user->name = $authed_twitter_user['full_name'];
                        $user->url = $authed_twitter_user['url'];
                        $user->avatar_url = $authed_twitter_user['avatar'];
                        $user->twitter_user_id = $authed_twitter_user['user_id'];
                        $user->twitter_username = $authed_twitter_user['user_name'];
                        $user->twitter_oauth_access_token = $token_array['oauth_token'];
                        $user->twitter_oauth_access_token_secret = $token_array['oauth_token_secret'];
                        $new_user_id = $user_dao->insert($user);
                        $user->id = $new_user_id;
                    }
                    Session::completeLogin($authed_twitter_user['user_id']);
                    $controller->addSuccessMessage('You have signed in.');
                }
            } else {
                $controller->addErrorMessage(
                    "Oops! There was a problem signing in with Twitter. Please try again.");
            }
        }
        return $controller->go();
    }
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
            'avatar'          => (string)$json_user->profile_image_url,
            'location'        => (string)$json_user->location,
            'description'     => (string)$json_user->description,
            'url'             => (string)$json_user->url,
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
            $translation = $error_code . ' ' . $translation;
        }
        return $translation;
    }
}
