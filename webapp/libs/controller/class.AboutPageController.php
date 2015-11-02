<?php

class AboutPageController extends MakerbaseController {
    //terms privacy about
    var $pages = array('terms', 'privacy', 'about', 'sponsor');

    public function control() {
        parent::control();

        $page = isset($_GET['p'])?$_GET['p']:'about';
        if (!in_array($page, $this->pages)) {
            $page = 'about';
        }
        $this->setViewTemplate($page.'.tpl');

        return $this->generateView();
    }
}
