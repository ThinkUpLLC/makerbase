<?php
/**
 * if user is on the whitelist:
 *    if user's maker doesn't exist
 *       create maker
 *       add maker to data store
 *       add maker to search
 *       add action
 *       add connection
 *
 *    add user's maker to attendee list
 *    force cache refresh on xoxo2015 page
 *    add success message
 * else
 *    add error message
 *
 */
class AttendEventController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();

        $maker_dao = new MakerMySQLDAO();
        if ($maker_dao->hasEventPermission('xoxo2015', $this->logged_in_user)) {
            if (isset($this->logged_in_user->maker_id)) {
                $users_maker = $maker_dao->getByID($this->logged_in_user->maker_id);
            } else {
                $users_maker = $this->addMaker();
            }
            $maker_dao->setIsAttendingEvent($users_maker, 'xoxo2015');

            // @TODO Expire cache for xoxo 2015 page
            SessionCache::put('success_message', 'You are going to XOXO 2015!');
        } else {
            SessionCache::put('error_message', 'Sorry, there was a problem adding you to the XOXO attendee list');
        }
        $this->redirect('/xoxo2015/');
    }

    private function addMaker() {
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
}
