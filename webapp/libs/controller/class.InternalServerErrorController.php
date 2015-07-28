<?php

class InternalServerErrorController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('500.tpl');
        if (isset($_SERVER["SERVER_PROTOCOL"])) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        }
        return $this->generateView();
    }
}
