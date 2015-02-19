<?php

class MakerController extends Controller {

    public function control() {
        $this->setViewTemplate('maker.tpl');
        return $this->generateView();
    }
}
