<?php

class SignOutController extends AuthController {

    public function authControl() {
        Session::logout();
        $controller = new LandingController(true);
        $controller->addSuccessMessage("You have signed out.");
        return $controller->go();
    }
}
