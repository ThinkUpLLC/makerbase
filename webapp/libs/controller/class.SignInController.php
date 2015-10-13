<?php

class SignInController extends MakerbaseController {

    public function control(){
        parent::control();
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

                    $user_dao = new UserMySQLDAO();
                    try {
                        $user = $user_dao->getByTwitterUserId($authed_twitter_user['user_id']);
                        $user_dao->updateLastLogin($user);
                        //If avatar, url, username, name, or tokens have changed on Twitter, update user in storage
                        if ($user->avatar_url !== $authed_twitter_user['avatar'] ||
                            $user->twitter_username !== $authed_twitter_user['user_name'] ||
                            $user->url !== $authed_twitter_user['url'] ||
                            $user->name !== $authed_twitter_user['full_name'] ||
                            $user->twitter_oauth_access_token !== $token_array['oauth_token'] ||
                            $user->twitter_oauth_access_token_secret !== $token_array['oauth_token_secret']
                            ) {
                            $user->name = $authed_twitter_user['full_name'];
                            $user->twitter_username = $authed_twitter_user['user_name'];
                            $user->url = $authed_twitter_user['url'];
                            $user->avatar_url = $authed_twitter_user['avatar'];
                            $user->twitter_oauth_access_token = $token_array['oauth_token'];
                            $user->twitter_oauth_access_token_secret = $token_array['oauth_token_secret'];
                            $user_dao->update($user);
                        }
                        if (!isset($user->maker_id)) {
                            //Get maker by twitter user id and set id
                            $maker_dao = new MakerMySQLDAO();
                            try {
                                $maker = $maker_dao->getByAutofill('twitter', $user->twitter_user_id);
                                $user->maker_id = $maker->id;
                                $user_dao->setMaker($user, $maker);
                            } catch (MakerDoesNotExistException $e) {
                                //there's no maker for this user; do nothing
                            }
                        }
                    } catch (UserDoesNotExistException $e) {
                        $user = new User();
                        $user->name = $authed_twitter_user['full_name'];
                        $user->url = $authed_twitter_user['url'];
                        $user->avatar_url = $authed_twitter_user['avatar'];
                        $user->twitter_user_id = $authed_twitter_user['user_id'];
                        $user->twitter_username = $authed_twitter_user['user_name'];
                        $user->twitter_oauth_access_token = $token_array['oauth_token'];
                        $user->twitter_oauth_access_token_secret = $token_array['oauth_token_secret'];
                        //Get maker by twitter user id and set id
                        $maker_dao = new MakerMySQLDAO();
                        try {
                            $maker = $maker_dao->getByAutofill('twitter', $user->twitter_user_id);
                            $user->maker_id = $maker->id;
                        } catch (MakerDoesNotExistException $e) {
                            //there's no maker for this user; set to null
                            $user->maker_id = null;
                        }
                        $new_user = $user_dao->insert($user);
                        $user->id = $new_user->id;
                        $user->uid = $new_user->uid;
                    }
                    MakerbaseSession::completeLogin($user->uid);
                    CacheHelper::expireLandingAndUserActivityCache($user->uid);
                    SessionCache::put('success_message', 'You have signed in.');

                    //Redirect user if redir is set
                    if (isset($_GET['redirect'])) {
                        if (!$this->redirect($_GET['redirect'])) {
                            $this->generateView(); //for testing
                        }
                    } else {
                        $this->redirect(Config::getInstance()->getValue('site_root_path'));
                    }
                }
            } else {
                $controller = new LandingController(true);
                $controller->addErrorMessage(
                    "Oops! There was a problem signing in with Twitter. Please try again.");
            }
        } elseif (isset($_GET['redirect'])) {
            $this->setViewTemplate('signin.tpl');
            if (MakerbaseSession::isLoggedIn()) {
                if (!$this->redirect($_GET['redirect'])) {
                    $this->generateView(); //for testing
                }
            }
            return $this->generateView();
        } else {
            $controller = new LandingController(true);
            return $controller->go();
        }
    }
}
