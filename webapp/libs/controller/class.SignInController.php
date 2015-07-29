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

                    //Twitter sign-in succceeded
                    $autofill_dao  = new AutofillMySQLDAO();
                    $waitlist_dao = new WaitlistMySQLDAO();

                    if ($autofill_dao->doesAutofillExist($authed_twitter_user['user_id'], 'twitter')) {
                        //User is whitelisted, log on in
                        $user_dao = new UserMySQLDAO();
                        try {
                            $user = $user_dao->getByTwitterUserId($authed_twitter_user['user_id']);
                            $user_dao->updateLastLogin($user);
                            //If avatar, url, username, or name has changed on Twitter, update it in storage
                            if ($user->avatar_url !== $authed_twitter_user['avatar'] ||
                                $user->twitter_username !== $authed_twitter_user['user_name'] ||
                                $user->url !== $authed_twitter_user['url'] ||
                                $user->name !== $authed_twitter_user['full_name']
                                ) {
                                $user->name = $authed_twitter_user['full_name'];
                                $user->twitter_username = $authed_twitter_user['user_name'];
                                $user->url = $authed_twitter_user['url'];
                                $user->avatar_url = $authed_twitter_user['avatar'];
                                $user_dao->update($user);
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
                            $new_user = $user_dao->insert($user);
                            $user->id = $new_user->id;
                            $user->uid = $new_user->uid;

                            $waitlist_dao->archive($authed_twitter_user['user_id'], 'twitter');
                        }
                        Session::completeLogin($user->uid);
                        CacheHelper::expireLandingAndUserActivityCache($user->uid);
                        SessionCache::put('success_message', 'You have signed in.');
                    } else {
                        $waitlist_dao->insert( $authed_twitter_user['user_id'], 'twitter',
                            $authed_twitter_user['user_name'], $authed_twitter_user['follower_count'],
                            $authed_twitter_user['is_verified'], $token_array['oauth_token'],
                            $token_array['oauth_token_secret']);
                        SessionCache::put('is_waitlisted', $authed_twitter_user);
                        $this->redirect(Config::getInstance()->getValue('site_root_path'));
                    }
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
            if (Session::isLoggedIn()) {
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
