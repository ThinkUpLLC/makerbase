<?php

class LandingController extends Controller {

    public function control() {
        $this->setViewTemplate('landing.tpl');
        return $this->generateView();
    }
}
