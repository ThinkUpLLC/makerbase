<?php
/**
 * Provide reusable event attendance functions.
 */
abstract class AttendEventAuthController extends MakerbaseAuthController {

    protected function addMaker() {
        // Populate maker object with user values
        $maker = new Maker();
        $maker->slug = AddController::getSlug($this->logged_in_user->twitter_username, $this->logged_in_user->name);
        $maker->name = $this->logged_in_user->name;
        $maker->url = (isset($this->logged_in_user->url))?$this->logged_in_user->url:'';
        $maker->avatar_url = $this->logged_in_user->avatar_url;
        $maker->autofill_network = 'twitter';
        $maker->autofill_network_id = $this->logged_in_user->twitter_user_id;
        $maker->autofill_network_username = $this->logged_in_user->twitter_username;

        // Insert maker into data store
        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->insert($maker);

        // Set the maker ID in the user object
        $user_dao = new UserMySQLDAO();
        $user_dao->setMaker($this->logged_in_user, $maker);

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
        return $maker;
    }

    protected static function expireCache($view_tpl) {
        $view_mgr = new ViewManager();
        //Clear logged in cached page
        $view_mgr->clearCache($view_tpl, self::getCacheKeyStringForReload($view_tpl, true));
        //Clear non-logged-in cache page
        $view_mgr->clearCache($view_tpl, self::getCacheKeyStringForReload($view_tpl, false));
    }

    protected static function getCacheKeyStringForReload($template, $include_logged_in_user = true) {
        $view_cache_key = array();
        if ($include_logged_in_user && Session::isLoggedIn()) {
            array_push($view_cache_key, Session::getLoggedInUser());
        }
        return '.ht'.$template.self::KEY_SEPARATOR.(implode($view_cache_key, self::KEY_SEPARATOR));
    }
}
