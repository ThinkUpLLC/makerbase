<?php

class LoadNetworkFriendsController extends MakerbaseController {
    /**
     * @var int Number days to wait till next friend refresh
     */
    static $number_days_till_friend_refresh = 5;

    public function control() {
        $this->disableCaching();

        $user_dao = new UserMySQLDAO();
        $users_to_refresh = $user_dao->getUsersWhoNeedFriendRefresh(20);

        if (count($users_to_refresh) > 0) {
            // Instantiate needed objects
            $api_accessor = new TwitterAPIAccessor();
            $cfg = Config::getInstance();
            $network_friend_dao = new NetworkFriendMySQLDAO();
            $connection_dao = new ConnectionMySQLDAO();
            $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
            $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');
            $results = array();

            //Loop through friendless users and get their friends
            foreach ($users_to_refresh as $user) {
                //Get user's auth tokens
                $oauth_token = $user->twitter_oauth_access_token;
                $oauth_secret = $user->twitter_oauth_access_token_secret;

                //If they weren't captured, use ours (at the risk of using too many API calls)
                if ($oauth_token == null || $oauth_secret == null) {
                    $oauth_token = $cfg->getValue('twitter_makerbase_oauth_access_token');
                    $oauth_secret = $cfg->getValue('twitter_makerbase_oauth_access_token_secret');
                }

                $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret, $oauth_token,
                    $oauth_secret);

                // echo "<pre>";
                // print_r($twitter_oauth);
                // echo "</pre>";
                try {
                    $friend_ids = $api_accessor->get5KFriends($user->twitter_user_id, $twitter_oauth);

                    $new_friend_count = 0;
                    // Populate network friends
                    foreach ($friend_ids->ids as $friend_id) {
                        if ($friend_id != $user->twitter_user_id) {
                            $new_friend_count += $network_friend_dao->insert($user->twitter_user_id, $friend_id,
                                'twitter');

                        }
                    }
                    $results[] = $user->twitter_username.": Added ".number_format($new_friend_count)
                        . " Twitter friends";
                    $user_dao->updateLastRefreshedFriends($user);

                    // Create connections based on friendships
                    // Set connections who are users
                    $friend_users = $user_dao->getUsersWhoAreFriends($user);
                    $new_connection_count = 0;
                    foreach ($friend_users as $friend) {
                        if ($connection_dao->insert($user, $friend)) {
                            $new_connection_count++;
                        }
                    }
                    $results[] = $user->twitter_username.": Added ".$new_connection_count. " user connections";

                    // Set connections who are makers
                    $maker_dao = new MakerMySQLDAO();
                    $friend_makers = $maker_dao->getMakersWhoAreFriends($user);
                    $new_connection_count = 0;
                    foreach ($friend_makers as $maker) {
                        if ($connection_dao->insert($user, $maker)) {
                            $new_connection_count++;
                        }
                    }
                    $results[] = $user->twitter_username.": Added ".$new_connection_count. " maker connections";

                    // Set connections which are products
                    $product_dao = new ProductMySQLDAO();
                    $friend_products = $product_dao->getProductsThatAreFriends($user);
                    $new_connection_count = 0;
                    foreach ($friend_products as $product) {
                        if ($connection_dao->insert($user, $product)) {
                            $new_connection_count++;
                        }
                    }
                    $results[] = $user->twitter_username.": Added ".$new_connection_count. " product connections";

                    $friend_ids = null;
                    $oauth_token = null;
                    $oauth_secret = null;
                } catch (APIErrorException $e) {
                    //If API credentials are incorrect, just mark and done and move on
                    //TODO Capture/indicate somewhere that there was an error, right now this fails silently
                    $user_dao->updateLastRefreshedFriends($user);
                }
            }

            $this->setJsonData($results);
        }

        return $this->generateView();
    }
}