<?php

class MakerbaseAuthController extends AuthController {
    /**
     * @var User $logged_in_user
     */
    var $logged_in_user;

    public function authControl() {
        $logged_in_user = Session::getLoggedInUser();
        $user_dao = new UserMySQLDAO();
        $user = $user_dao->get($logged_in_user);
        $this->addToView('logged_in_user', $user);
        $this->logged_in_user = $user;

        $cfg = Config::getInstance();
        $this->addToView('thinkup_uid', $cfg->getValue('thinkup_uid'));
    }
}
