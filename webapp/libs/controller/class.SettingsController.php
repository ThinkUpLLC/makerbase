<?php

class SettingsController extends MakerbaseAuthController {

    public function authControl() {
        parent::authControl();
        $this->redirect('/u/'.$this->logged_in_user->uid);
    }
}
