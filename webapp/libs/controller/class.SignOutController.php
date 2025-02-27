<?php

class SignOutController extends AuthController {

    public function authControl() {
        MakerbaseSession::logout();
        SessionCache::put('success_message', "You have signed out.");
        $this->redirect(Config::getInstance()->getValue('site_root_path'));
    }
}
