<?php

class AddController extends MakerbaseAuthController {
    /**
     * Message to show users who are frozen when they're denied edit rights.
     * @var string
     */
    private $frozen_user_message = 'Unable to save your changes. Please try again in a little while.';

    public function authControl() {
        parent::authControl();
        $this->setViewTemplate('add.tpl');

        $valid_objects = array('maker', 'product', 'role', 'madewith', 'inspiration');
        if (in_array($_GET['object'], $valid_objects)) {
            $this->addToView('object', $_GET['object']);

            if (isset($_GET['q'])) {
                if ($_GET['object'] == 'product') {
                    $this->addiOSAppsToView($_GET['q']);
                    $this->addSearchResultsToView($_GET['q'], 'product');
                    $this->addGitHubReposToView($_GET['q'], 'product');
                } elseif ($_GET['object'] == 'maker') {
                    $this->addSearchResultsToView($_GET['q'], 'maker');
                }
                $this->addTwitterUsersToView($_GET['q']);
                $this->addTargetToView();
                return $this->generateView();
            } elseif ($_GET['object'] == 'maker' && $this->hasSubmittedMakerForm()) {
                if (!$this->logged_in_user->is_frozen) {
                    CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                    $this->addMaker();
                } else {
                    $this->addErrorMessage($this->frozen_user_message);
                }
                return $this->generateView();
            } elseif ($_GET['object'] == 'product' && $this->hasSubmittedProductForm()) {
                if (!$this->logged_in_user->is_frozen) {
                    CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                    $this->addProduct();
                } else {
                    $this->addErrorMessage($this->frozen_user_message);
                }
                return $this->generateView();
            } elseif ($_GET['object'] == 'role' && $this->hasSubmittedRoleForm()) {
                if (!$this->logged_in_user->is_frozen) {
                    CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                    $this->addRole();
                } else {
                    SessionCache::put('error_message', $this->frozen_user_message);
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
                } else {
                    SessionCache::put('error_message', $this->frozen_user_message);
                }
                $this->redirect('/p/'.$_POST['originate_uid'].'/'.$_POST['originate_slug']);
            } elseif ($_GET['object'] == 'inspiration' && $this->hasSubmittedInspirationForm()) {
                if (!$this->logged_in_user->is_frozen) {
                    CacheHelper::expireLandingAndUserActivityCache($this->logged_in_user->uid);
                    $this->addInspiration();
                } else {
                    SessionCache::put('error_message', $this->frozen_user_message);
                }
                $this->redirect('/m/'.$_POST['originate_uid'].'/'.$_POST['originate_slug']);
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

    private function hasSubmittedInspirationForm() {
        return (
            isset($_POST['maker_uid'])
            && isset($_POST['inspiration_description'])
            && isset($_POST['originate_uid'])
            && isset($_POST['originate_slug'])
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
            $https_avatar_url = str_replace('http://', 'https://', $twitter_users[0]['avatar']);
            $this->addToView('avatar_url', $https_avatar_url);
            $this->addToView('url', $twitter_users[0]['url']);
            $this->addToView('network_username', $twitter_users[0]['user_name']);
            $this->addToView('slug', $twitter_users[0]['user_name']);
            $this->addToView('network', 'twitter');
            $this->addToView('network_id', $twitter_users[0]['user_id']);
        }
    }

    private function addSearchResultsToView($term, $index) {
        $start_time = microtime(true);

        if (substr($term, 0, 1) === '@') {
            $term = substr($term, 1, (strlen($term) -1 ));
        }

        $client = new Elasticsearch\Client();

        $search_params = array();
        $search_params['index'] = $index.'_index';
        $search_params['type']  = $index.'_type';
        $search_params['body']['query']['multi_match']['fields'] = array('name');
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
            $this->addToView('existing_objects_hit_type', substr($index, 0, 1));
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

    private function addGitHubReposToView($term) {
        $start_time = microtime(true);
        $api_accessor = new GitHubSearchAPIAccessor();
        $repos = $api_accessor->searchRepos($term);
        $end_time = microtime(true);

        if (Profiler::isEnabled()) {
            $total_time = $end_time - $start_time;
            $profiler = Profiler::getInstance();
            $profiler->add($total_time, "GitHub repo search", false);
        }
        $this->addToView('github_repos', $repos);
    }

    private function addTargetToView() {
        if (isset($_GET['object']) && isset($_GET['target'])) {
            if ($_GET['object'] == 'maker') {
                // If originating object is a maker, the target is a product
                $product_dao = new ProductMySQLDAO();
                try {
                    $to_product = $product_dao->get($_GET['target']);
                    $this->addToView('to_product', $to_product);
                } catch (ProductDoesNotExistException $e) {
                    //do nothing, move along
                }
            } elseif ($_GET['object'] == 'product') {
                // If originating object is a product, the target is a maker
                $maker_dao = new MakerMySQLDAO();
                try {
                    $to_maker = $maker_dao->get($_GET['target']);
                    $this->addToView('to_maker', $to_maker);
                } catch (MakerDoesNotExistException $e) {
                    //do nothing, move along
                }
            }
        }
    }

    private function addInspiration() {
        $maker_dao = new MakerMySQLDAO();
        try {
            $inspirer_maker = $maker_dao->get($_POST['maker_uid']);
            $maker = $maker_dao->get($_POST['originate_uid']);

            // Is the logged in user looking at zer own maker page?
            if (isset($maker->autofill_network_id) && isset($maker->autofill_network)
                && $maker->autofill_network == 'twitter'
                && $this->logged_in_user->twitter_user_id == $maker->autofill_network_id) {

                //Add inspiration
                $inspiration = new Inspiration();
                $inspiration->inspirer_maker_id = $inspirer_maker->id;
                $inspiration->description = $_POST['inspiration_description'];
                $inspiration->maker_id = $maker->id;

                $inspiration_dao = new InspirationMySQLDAO();
                $inspiration_dao->insert($inspiration);

                //Add new connection
                $connection_dao = new ConnectionMySQLDAO();
                $connection_dao->insert($this->logged_in_user, $inspirer_maker);

                //Add new action
                $action = new Action();
                $action->user_id = $this->logged_in_user->id;
                $action->severity = Action::SEVERITY_NORMAL;
                $action->object_id = $inspirer_maker->id;
                $action->object_type = get_class($inspirer_maker);
                $action->object2_id = $maker->id;
                $action->object2_type = get_class($maker);
                $action->ip_address = $_SERVER['REMOTE_ADDR'];
                $action->action_type = 'inspire';

                // Add extra metadata to inspiration
                $inspiration->maker = $maker;
                $inspiration->inspirer_maker = $inspirer_maker;
                $action->metadata = json_encode($inspiration);

                $action_dao = new ActionMySQLDAO();
                $action_dao->insert($action);

                //Force cache refresh
                CacheHelper::expireCache('maker.tpl', $maker->uid, $maker->slug);

                SessionCache::put('success_message', 'You said '.htmlspecialchars($inspirer_maker->name).
                    ' inspires you.');

            } else {
                SessionCache::put('error_message', 'Sorry, cannot add that inspiration.');
            }
        } catch (MakerDoesNotExistException $e) {
            SessionCache::put('error_message', 'That maker does not exist.');
        }
    }

    private function addRole() {
        $maker_dao = new MakerMySQLDAO();
        $product_dao = new ProductMySQLDAO();
        try {
            $maker = $maker_dao->get($_POST['maker_uid']);
            $product = $product_dao->get($_POST['product_uid']);

            //Check dates
            //Is the start date valid?
            $start_date_str = (empty($_POST['start_date']))?null:$_POST['start_date'];
            if (isset($start_date_str)) {
                if (!Role::isValidDateString($start_date_str)) {
                    SessionCache::put('error_message',
                        'That start date doesn\'t look right. Please try again.');
                    return;
                }
            }
            //Is the end date valid?
            $end_date_str = (empty($_POST['end_date']))?null:$_POST['end_date'];
            if (isset($end_date_str)) {
                if (!Role::isValidDateString($end_date_str)) {
                    SessionCache::put('error_message',
                        'That end date doesn\'t look right. Please try again.');
                    return;
                }
            }
            //Is the start date before or the same as the end date?
            $start_date = (empty($_POST['start_date']))?null:$_POST['start_date']."-01";
            $end_date = (empty($_POST['end_date']))?null:$_POST['end_date']."-01";
            if (isset($start_date) && isset($end_date)) {
                $start_date_time = strtotime($start_date);
                $end_date_time = strtotime($end_date);
                if ($start_date_time > $end_date_time) {
                    SessionCache::put('error_message',
                        'The start date has to be before the end date. Please try again.');
                    return;
                }
            }

            $role = new Role();
            $role->maker_id = $maker->id;
            $role->product_id = $product->id;
            $role->start = (empty($_POST['start_date']))?null:$_POST['start_date'].'-01';
            $role->end = (empty($_POST['end_date']))?null:$_POST['end_date'].'-01';
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

            //User has added a role
            if (!$this->logged_in_user->has_added_role) {
                $user_dao = new UserMySQLDAO();
                $user_dao->hasAddedRole($this->logged_in_user);
            }

            //Force cache refresh
            CacheHelper::expireCache('product.tpl', $product->uid, $product->slug);
            CacheHelper::expireCache('maker.tpl', $maker->uid, $maker->slug);

            SessionCache::put('success_message', 'You added '.htmlspecialchars($maker->name).' to '.
                htmlspecialchars($product->name).'.');
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
            $existing_madewith = $madewith_dao->getByProductUsedProductID($product->id, $used_product->id);
            if (isset($existing_madewith)) {
                $madewith_dao->unarchive($existing_madewith->uid);
                $existing_madewith->archived = false;
                $inserted_madewith = $existing_madewith;
            } else {
                $inserted_madewith = $madewith_dao->insert($madewith);
            }

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

            SessionCache::put('success_message', 'You said '.htmlspecialchars($product->name).' was made with '
                .htmlspecialchars($used_product->name).'.');
        } catch (ProductDoesNotExistException $e) {
            SessionCache::put('error_message', 'That project does not exist.');
        }
    }

    private function addMaker() {
        if (empty($_POST['name'])) {
            $this->addErrorMessage('Name is required');
        } elseif (!empty($_POST['url']) && filter_var($_POST['url'], FILTER_VALIDATE_URL) === false) {
            $this->addErrorMessage("That doesn't look like a valid web site URL. Please try again.");
        } elseif (!empty($_POST['avatar_url']) && filter_var($_POST['avatar_url'], FILTER_VALIDATE_URL) === false) {
            $this->addErrorMessage("That doesn't look like a valid avatar URL. Please try again.");
        } else {
            $maker = new Maker();
            $maker->slug = $this->getSlug($_POST['slug'], $_POST['name']);
            $maker->name = $_POST['name'];
            $maker->url = $_POST['url'];
            $maker->avatar_url = $_POST['avatar_url'];
            if (isset($_POST['network_id']) && isset($_POST['network']) && isset($_POST['network_username'])
                && !empty($_POST['network_id']) && !empty($_POST['network']) && !empty($_POST['network_username']) ) {

                $maker->autofill_network = $_POST['network'];
                $maker->autofill_network_id = $_POST['network_id'];
                $maker->autofill_network_username = $_POST['network_username'];
            } else {
                $maker->autofill_network = null;
                $maker->autofill_network_id = null;
                $maker->autofill_network_username = null;
            }

            $maker_dao = new MakerMySQLDAO();
            $maker = $maker_dao->insert($maker);

            //If there is a Makerbase user for this Twitter-autofilled user, set users.maker_id
            if (isset($maker->autofill_network_id) && isset($maker->autofill_network)
                && $maker->autofill_network === 'twitter') {
                $user_dao = new UserMySQLDAO();
                try {
                    $user = $user_dao->getByTwitterUserId($_POST['network_id']);
                    $user_dao->setMaker($user, $maker);
                } catch (UserDoesNotExistException $e) {
                    //do nothing, user doesn't exist
                }
            }

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

            //User has added a maker
            if (!$this->logged_in_user->has_added_maker) {
                $user_dao = new UserMySQLDAO();
                $user_dao->hasAddedMaker($this->logged_in_user);
            }

            $tweet_link = '';
            //Add tweet link
            if (isset($maker->autofill_network_id) && isset($maker->autofill_network)
                && $maker->autofill_network == 'twitter') {
                $tweet_body = '@'.$maker->autofill_network_username
                    .' Hey, I just added you to Makerbase.'
                    .' Add your projects & the makers who inspire you';
                $tweet_link = ' <a href="https://twitter.com/share?text='.urlencode($tweet_body)
                    .'" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,'
                    .'scrollbars=yes,height=600,width=600\');return false;">Let @'
                    .$maker->autofill_network_username.' know</a>.';
            }

            // If adding from a product page, insert a role and redirect back to product
            if (isset($_POST['add_role_to_product_uid'])) {
                $product_dao = new ProductMySQLDAO();
                try {
                    $product = $product_dao->get($_POST['add_role_to_product_uid']);

                    //Add role
                    $role = $this->insertBlankRole($maker, $product);

                    //Add new connection
                    $connection_dao->insert($this->logged_in_user, $product);

                    //Add new action for role
                    $this->insertActionForRole($maker, $product, $role);

                    //User has added a role
                    if (!$this->logged_in_user->has_added_role) {
                        $user_dao = new UserMySQLDAO();
                        $user_dao->hasAddedRole($this->logged_in_user);
                    }

                    SessionCache::put('success_message', 'You added '.htmlspecialchars($maker->name).' to '
                        .htmlspecialchars($product->name).'.'.$tweet_link);
                    $this->redirect('/p/'.$product->uid.'/'.$product->slug);
                } catch (ProductDoesNotExistException $e) {
                    SessionCache::put('success_message', 'You added '.htmlspecialchars($maker->name).'.'
                        .$tweet_link);
                    $this->redirect('/m/'.$maker->uid.'/'.$maker->slug);
                }
            } else {
                SessionCache::put('success_message', 'You added '.htmlspecialchars($maker->name).'.'.$tweet_link);
                $this->redirect('/m/'.$maker->uid.'/'.$maker->slug);
            }
        }
    }

    private function insertBlankRole(Maker $maker, Product $product) {
        $role = new Role();
        $role->maker_id = $maker->id;
        $role->product_id = $product->id;
        $role->start = null;
        $role->end = null;
        $role->role = '';
        $role_dao = new RoleMySQLDAO();
        $role = $role_dao->insert($role);
        //Force cache refresh
        CacheHelper::expireCache('product.tpl', $product->uid, $product->slug);
        CacheHelper::expireCache('maker.tpl', $maker->uid, $maker->slug);
        return $role;
    }

    private function insertActionForRole(Maker $maker, Product $product, Role $role) {
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
    }

    private function addProduct() {
        if (empty($_POST['name'])) {
            $this->addErrorMessage('Name is required');
        } elseif (!empty($_POST['url']) && filter_var($_POST['url'], FILTER_VALIDATE_URL) === false) {
            $this->addErrorMessage("That doesn't look like a valid web site URL. Please try again.");
        } elseif (!empty($_POST['avatar_url']) && filter_var($_POST['avatar_url'], FILTER_VALIDATE_URL) === false) {
            $this->addErrorMessage("That doesn't look like a valid avatar URL. Please try again.");
        } elseif (empty($_POST['name'])) {
            $this->addErrorMessage("Please enter a name and try again.");
        } else {
            $product = new Product();
            $product->slug = $this->getSlug($_POST['slug'], $_POST['name']);
            $product->name = $_POST['name'];
            $product->description = $_POST['description'];
            $product->url = $_POST['url'];
            $product->avatar_url = $_POST['avatar_url'];
            if (isset($_POST['network_id']) && isset($_POST['network']) && isset($_POST['network_username'])
                && !empty($_POST['network_id']) && !empty($_POST['network']) && !empty($_POST['network_username']) ) {

                $product->autofill_network = $_POST['network'];
                $product->autofill_network_id = $_POST['network_id'];
                $product->autofill_network_username = $_POST['network_username'];
            } else {
                $product->autofill_network = null;
                $product->autofill_network_id = null;
                $product->autofill_network_username = null;
            }

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

            //User has added a product
            if (!$this->logged_in_user->has_added_product) {
                $user_dao = new UserMySQLDAO();
                $user_dao->hasAddedProduct($this->logged_in_user);
            }

            // If adding from a maker page, insert a role and redirect back to maker
            if (isset($_POST['add_role_to_maker_uid'])) {
                $maker_dao = new MakerMySQLDAO();
                try {
                    $maker = $maker_dao->get($_POST['add_role_to_maker_uid']);

                    //Add role
                    $role = $this->insertBlankRole($maker, $product);

                    //Add new connection
                    $connection_dao->insert($this->logged_in_user, $maker);

                    //Add new action for role
                    $this->insertActionForRole($maker, $product, $role);

                    //User has added a role
                    if (!$this->logged_in_user->has_added_role) {
                        $user_dao = new UserMySQLDAO();
                        $user_dao->hasAddedRole($this->logged_in_user);
                    }

                    SessionCache::put('success_message', 'You added '.htmlspecialchars($product->name).' to '
                        .htmlspecialchars($maker->name).'.');
                    $this->redirect('/m/'.$maker->uid.'/'.$maker->slug);
                } catch (MakerDoesNotExistException $e) {
                    SessionCache::put('success_message', 'You added '.htmlspecialchars($product->name).'.');
                    $this->redirect('/p/'.$product->uid.'/'.$product->slug);
                }
            } else {
                SessionCache::put('success_message', 'You added '.htmlspecialchars($product->name).'.');
                $this->redirect('/p/'.$product->uid.'/'.$product->slug);
            }
        }
    }

    public static function getSlug($slug, $name) {
        if (empty($slug)) {
            return strtolower(preg_replace("/[^A-Za-z0-9]/", "", $name));
        } else {
            return strtolower(preg_replace("/[^A-Za-z0-9]/", "", $slug));
        }
    }
}
