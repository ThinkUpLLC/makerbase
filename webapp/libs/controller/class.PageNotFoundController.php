<?php

class PageNotFoundController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('404.tpl');
        return $this->generateView();
    }
}
