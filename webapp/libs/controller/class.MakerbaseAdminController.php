<?php
/**
 * Only logged-in, admin users can perform this action.
 */
abstract class MakerbaseAdminController extends AuthController {

    protected function preAuthControl() {
        if (MakerbaseSession::isLoggedIn()) {
            $logged_in_user = MakerbaseSession::getLoggedInUser();
            $user_dao = new UserMySQLDAO();
            $user = $user_dao->get($logged_in_user);

            //Set admin status
            $cfg = Config::getInstance();
            $admins = $cfg->getValue('admins');
            $user->is_admin = in_array($user->twitter_username, $admins);

            $this->addToView('logged_in_user', $user);
            $this->logged_in_user = $user;

            $this->addToView('thinkup_uid', $cfg->getValue('thinkup_uid'));
            $this->addToView('image_proxy_sig', $cfg->getValue('image_proxy_sig'));

            if (!$user->is_admin) {
                $controller = new AccessDeniedController(true);
                return $controller->go();
            } else {
                return false;
            }
        } else { //Not logged in
            return $this->bounce();
        }
    }
}