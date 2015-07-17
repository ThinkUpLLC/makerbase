<?php

class AddController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();
        $this->setViewTemplate('add.tpl');

        if ($this->logged_in_user->is_frozen && !isset($_GET['q'])) {
            SessionCache::put('error_message', 'Unable to save your changes. Please try again in a little while.');
        }

        $valid_objects = array('maker', 'product', 'role', 'madewith');
        if (in_array($_GET['object'], $valid_objects)) {
            $this->addToView('object', $_GET['object']);

            if (isset($_GET['q'])) {
                $this->addSearchResultsToView($_GET['q']);
                $this->addTwitterUsersToView($_GET['q']);
                if ($_GET['object'] == 'product') {
                    $this->addiOSAppsToView($_GET['q']);
                }
                // Transfer cached user messages to the view
                $this->setUserMessages();
                return $this->generateView();
            } elseif ($_GET['object'] == 'maker' && $this->hasSubmittedMakerForm()) {
                if (!$this->logged_in_user->is_frozen) {
                    CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                    $this->addMaker();
                }
                // Transfer cached user messages to the view
                $this->setUserMessages();
                return $this->generateView();
            } elseif ($_GET['object'] == 'product' && $this->hasSubmittedProductForm()) {
                if (!$this->logged_in_user->is_frozen) {
                    CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                    $this->addProduct();
                }
                // Transfer cached user messages to the view
                $this->setUserMessages();
                return $this->generateView();
            } elseif ($_GET['object'] == 'role' && $this->hasSubmittedRoleForm()) {
                if (!$this->logged_in_user->is_frozen) {
                    CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                    $this->addRole();
                }
                if ($_POST['originate'] == 'maker') {
                    $this->redirect('/m/'.$_POST['originate_uid'].'/'.$_POST['originate_slug']);
                } else {
                    $this->redirect('/p/'.$_POST['originate_uid'].'/'.$_POST['originate_slug']);
                }
            } elseif ($_GET['object'] == 'madewith' && $this->hasSubmittedMadeWithForm()) {
                if (!$this->logged_in_user->is_frozen) {
                    CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                    $this->addMadeWith();
                }
                $this->redirect('/p/'.$_POST['originate_uid'].'/'.$_POST['originate_slug']);
            }
        } else {
            $this->redirect(Config::getInstance()->getValue('site_root_path'));
        }
    }

    private function hasSubmittedMakerForm() {
        return (
            isset($_POST['slug'])
            && isset($_POST['name'])
            && isset($_POST['url'])
            && isset($_POST['avatar_url'])
            );
    }

    private function hasSubmittedRoleForm() {
        return (
            isset($_POST['product_uid'])
            && isset($_POST['maker_uid'])
            && isset($_POST['start_date'])
            && isset($_POST['end_date'])
            && isset($_POST['role'])
            && isset($_POST['originate'])
            && isset($_POST['originate_uid'])
            && isset($_POST['originate_slug'])
            );
    }

    private function hasSubmittedMadeWithForm() {
        return (
            isset($_POST['product_uid'])
            && isset($_POST['product_used_uid'])
            && isset($_POST['originate_uid'])
            && isset($_POST['originate_slug'])
            );
    }

    private function hasSubmittedProductForm() {
        return (
            isset($_POST['slug'])
            && isset($_POST['name'])
            && isset($_POST['description'])
            && isset($_POST['url'])
            && isset($_POST['avatar_url'])
            );
    }

    private function addTwitterUsersToView($twitter_username) {
        $start_time = microtime(true);
        $cfg = Config::getInstance();
        $oauth_consumer_key = $cfg->getValue('twitter_oauth_consumer_key');
        $oauth_consumer_secret = $cfg->getValue('twitter_oauth_consumer_secret');

        $twitter_oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret,
            $this->logged_in_user->twitter_oauth_access_token,
            $this->logged_in_user->twitter_oauth_access_token_secret);

        $api_accessor = new TwitterAPIAccessor();
        $twitter_users = $api_accessor->searchUsers($twitter_username, $twitter_oauth);
        $end_time = microtime(true);

        if (Profiler::isEnabled()) {
            $total_time = $end_time - $start_time;
            $profiler = Profiler::getInstance();
            $profiler->add($total_time, "Twitter search", false);
        }
        $this->addToView('twitter_users', $twitter_users);

        //If search term starts with an @ sign, prefill the matching Twitter user in results
        if (substr($twitter_username, 0, 1) === '@') {
            $this->addToView('name', $twitter_users[0]['full_name']);
            $this->addToView('avatar_url', $twitter_users[0]['avatar']);
            $this->addToView('url', $twitter_users[0]['url']);
            $this->addToView('network_username', $twitter_users[0]['user_name']);
            $this->addToView('slug', $twitter_users[0]['user_name']);
            $this->addToView('network', 'twitter');
            $this->addToView('network_id', $twitter_users[0]['user_id']);
        }
    }

    private function addSearchResultsToView($term) {
        $start_time = microtime(true);
        $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
        $this->addToView('image_proxy_sig', $image_proxy_sig);

        if (substr($term, 0, 1) === '@') {
            $term = substr($term, 1, (strlen($term) -1 ));
        }

        $client = new Elasticsearch\Client();

        $search_params = array();
        $search_params['index'] = 'maker_product_index';
        $search_params['type']  = 'maker_product_type';
        $search_params['body']['query']['multi_match']['fields'] = array('slug', 'name', 'description', 'url');
        $search_params['body']['query']['multi_match']['query'] = urlencode($term);
        $search_params['body']['query']['multi_match']['type'] = 'phrase_prefix';

        $return_document = $client->search($search_params);
        $end_time = microtime(true);

        if (Profiler::isEnabled()) {
            $total_time = $end_time - $start_time;
            $profiler = Profiler::getInstance();
            $profiler->add($total_time, "Elasticsearch", false);
        }

        if (count($return_document['hits']['hits']) > 0) {
            $this->addToView('existing_objects', $return_document['hits']['hits']);
        }
    }

    private function addiOSAppsToView($term) {
        $start_time = microtime(true);
        $ios_api_accessor = new iOSAppStoreAPIAccessor();
        $ios_apps = $ios_api_accessor->searchApps($term);
        $end_time = microtime(true);

        if (Profiler::isEnabled()) {
            $total_time = $end_time - $start_time;
            $profiler = Profiler::getInstance();
            $profiler->add($total_time, "App Store search", false);
        }
        $this->addToView('ios_apps', $ios_apps);
    }

    private function addRole() {
        $maker_dao = new MakerMySQLDAO();
        $product_dao = new ProductMySQLDAO();
        try {
            $maker = $maker_dao->get($_POST['maker_uid']);
            $product = $product_dao->get($_POST['product_uid']);

            $role = new Role();
            $role->maker_id = $maker->id;
            $role->product_id = $product->id;
            $role->start = ($_POST['start_date'] == '')?null:$_POST['start_date'].'-01';
            $role->end = ($_POST['end_date'] == '')?null:$_POST['end_date'].'-01';
            $role->role = $_POST['role'];

            $role_dao = new RoleMySQLDAO();
            $role = $role_dao->insert($role);

            //Add new connection
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->insert($this->logged_in_user, $maker);
            $connection_dao->insert($this->logged_in_user, $product);

            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_NORMAL;
            $action->object_id = $maker->id;
            $action->object_type = get_class($maker);
            $action->object2_id = $product->id;
            $action->object2_type = get_class($product);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = 'associate';

            $role->maker = $maker;
            $role->product = $product;

            $action->metadata = json_encode($role);

            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);

            //Force cache refresh
            CacheHelper::expireCache('product.tpl', $product->uid, $product->slug);
            CacheHelper::expireCache('maker.tpl', $maker->uid, $maker->slug);

            SessionCache::put('success_message', 'You added '.$maker->name.' to '.$product->name.'.');
        } catch (MakerDoesNotExistException $e) {
            SessionCache::put('error_message', 'That maker does not exist.');
        } catch (ProductDoesNotExistException $e) {
            SessionCache::put('error_message', 'That project does not exist.');
        }
    }

    private function addMadeWith() {
        $product_dao = new ProductMySQLDAO();
        try {
            $product = $product_dao->get($_POST['product_uid']);
            $used_product = $product_dao->get($_POST['product_used_uid']);

            $madewith = new MadeWith();
            $madewith->product_id = $product->id;
            $madewith->used_product_id = $used_product->id;

            $madewith_dao = new MadeWithMySQLDAO();
            $inserted_madewith = $madewith_dao->insert($madewith);

            //Add new connection
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->insert($this->logged_in_user, $used_product);
            $connection_dao->insert($this->logged_in_user, $product);

            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_NORMAL;
            $action->object_id = $product->id;
            $action->object_type = get_class($product);
            $action->object2_id = $used_product->id;
            $action->object2_type = get_class($used_product);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = 'made with';

            $inserted_madewith->product = $product;
            $inserted_madewith->used_product = $used_product;
            $action->metadata = json_encode($inserted_madewith);

            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);

            //Force cache refresh
            CacheHelper::expireCache('product.tpl', $product->uid, $product->slug);
            CacheHelper::expireCache('product.tpl', $used_product->uid, $used_product->slug);

            SessionCache::put('success_message', 'You said '.$product->name.' was made with '.$used_product->name.'.');
        } catch (ProductDoesNotExistException $e) {
            SessionCache::put('error_message', 'That project does not exist.');
        }
    }

    private function addMaker() {
        if (empty($_POST['name'])) {
            $this->addErrorMessage('Name is required');
        } else {
            $maker = new Maker();
            $maker->slug = $this->getSlug($_POST['slug'], $_POST['name']);
            $maker->name = $_POST['name'];
            $maker->url = $_POST['url'];
            $maker->avatar_url = $_POST['avatar_url'];

            $maker_dao = new MakerMySQLDAO();
            $maker = $maker_dao->insert($maker);

            // Add new maker to Elasticsearch
            SearchHelper::indexMaker($maker);

            //Add new connection
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->insert($this->logged_in_user, $maker);

            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_NORMAL;
            $action->object_id = $maker->id;
            $action->object_type = get_class($maker);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = 'create';
            $action->metadata = json_encode($maker);

            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);

            //Add autofill
            if (isset($_POST['network_id']) && isset($_POST['network'])
                && !empty($_POST['network_id']) && !empty($_POST['network'])) {
                $autofill_dao = new AutofillMySQLDAO();
                $network_username = (isset($_POST['network_username']) && !empty($_POST['network_username']))?
                    $_POST['network_username']:null;
                $autofill_dao->insertMakerAutofill($_POST['network_id'], $_POST['network'], $network_username,
                    $maker->id);

                //Flip waitlist bit, user can sign in now
                $waitlist_dao = new WaitlistMySQLDAO();
                $waitlist_dao->archive($_POST['network_id'], $_POST['network']);

                //Set up tweet link in success message
                if ($_POST['network'] == 'twitter') {
                    if (isset($network_username))  {
                        $twitter_username = '@'.$_POST['network_username'];
                        $tweet_body = $twitter_username
                            .' Hey, I just added you to Makerbase.'
                            .' Now you can edit your page & list projects/makers who inspire you';
                        $tweet_link = ' <a href="https://twitter.com/share?text='.urlencode($tweet_body)
                            .'" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,'
                            .'scrollbars=yes,height=600,width=600\');return false;">Let '
                            .$twitter_username.' know</a>.';
                    }
                } else {
                    $tweet_link = '';
                }
            }

            SessionCache::put('success_message', 'You added '.$maker->name.'.'.$tweet_link);
            $this->redirect('/m/'.$maker->uid.'/'.$maker->slug);
        }
    }

    private function addProduct() {
        if (empty($_POST['name'])) {
            $this->addErrorMessage('Name is required');
        } else {
            $product = new Product();
            $product->slug = $this->getSlug($_POST['slug'], $_POST['name']);
            $product->name = $_POST['name'];
            $product->description = $_POST['description'];
            $product->url = $_POST['url'];
            $product->avatar_url = $_POST['avatar_url'];

            $product_dao = new ProductMySQLDAO();

            //Insert maker (this may throw a DuplicateMakerException)
            $product = $product_dao->insert($product);

            // Add new product to Elasticsearch
            SearchHelper::indexProduct($product);

            //Add new connection
            $connection_dao = new ConnectionMySQLDAO();
            $connection_dao->insert($this->logged_in_user, $product);

            //Add new action
            $action = new Action();
            $action->user_id = $this->logged_in_user->id;
            $action->severity = Action::SEVERITY_NORMAL;
            $action->object_id = $product->id;
            $action->object_type = get_class($product);
            $action->ip_address = $_SERVER['REMOTE_ADDR'];
            $action->action_type = 'create';
            $action->metadata = json_encode($product);

            $action_dao = new ActionMySQLDAO();
            $action_dao->insert($action);

            //Add autofill
            if (isset($_POST['network_id']) && isset($_POST['network'])
                && !empty($_POST['network_id']) && !empty($_POST['network'])) {
                $autofill_dao = new AutofillMySQLDAO();
                $network_username = (isset($_POST['network_username']) && !empty($_POST['network_username']))?
                    $_POST['network_username']:null;
                $autofill_dao->insertProductAutofill($_POST['network_id'], $_POST['network'], $network_username,
                    $product->id);

                //Flip waitlist bit, user can sign in now
                $waitlist_dao = new WaitlistMySQLDAO();
                $waitlist_dao->archive($_POST['network_id'], $_POST['network']);
            }

            SessionCache::put('success_message', 'You added '.$product->name.'.');
            $this->redirect('/p/'.$product->uid.'/'.$product->slug);
        }
    }

    private function getSlug($slug, $name) {
        if (empty($slug)) {
            return strtolower(preg_replace("/[^A-Za-z0-9]/", "", $name));
        } else {
            return strtolower(preg_replace("/[^A-Za-z0-9]/", "", $slug));
        }
    }
}
