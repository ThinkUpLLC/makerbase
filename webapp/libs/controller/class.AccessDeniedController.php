<?php

class AccessDeniedController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('access-denied.tpl');
        return $this->generateView();
    }
}
