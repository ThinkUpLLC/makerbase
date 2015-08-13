<?php

class PageNotFoundController extends MakerbaseController {

    public function control() {
        parent::control();
        error_log('HTTP 404 '.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        $this->setViewTemplate('404.tpl');
        if (isset($_SERVER["SERVER_PROTOCOL"])) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
        }
        return $this->generateView();
    }
}
