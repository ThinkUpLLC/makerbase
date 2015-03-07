<?php

class SignInController extends Controller {

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
                $api_accessor = new TwitterAPIAccessor();
                $authed_twitter_user = $api_accessor->verifyCredentials($twitter_oauth);

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
}
