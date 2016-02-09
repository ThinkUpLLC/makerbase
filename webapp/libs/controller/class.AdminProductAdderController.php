<?php

class AdminProductAdderController extends MakerbaseAdminController {

    public function authControl() {
        $this->setViewTemplate('admin-productadder.tpl');
        $this->disableCaching();

        if (isset($_GET['type']) && $_GET['type'] !== 'frames') {
            $this->addToView('type', 'posts');
        } else {
            $this->addToView('type', 'frames');
        }

        $ph_token = Config::getInstance()->getValue('producthunt_token');
        $this->addToView('ph_token', $ph_token);

        return $this->generateView();
    }
}
