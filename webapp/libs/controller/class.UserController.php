<?php

class UserController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('user.tpl');

        if ($this->shouldRefreshCache() ) {
            $user_dao = new UserMySQLDAO();
            $user = $user_dao->get($_GET['uid']);
            $this->addToView('user', $user);

            // Get actions
            $action_dao = new ActionMySQLDAO();
            $actions = $action_dao->getUserActivities($user->id);
            $this->addToView('actions', $actions);

            $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
            $this->addToView('image_proxy_sig', $image_proxy_sig);
        }
        return $this->generateView();
    }
}
