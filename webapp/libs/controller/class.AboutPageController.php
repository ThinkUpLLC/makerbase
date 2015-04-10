<?php

class AboutPageController extends MakerbaseController {
    //terms privacy about
    var $pages = array('terms', 'privacy', 'about');

    public function control() {
        parent::control();

        if ($this->shouldRefreshCache() ) {
            $page = isset($_GET['p'])?$_GET['p']:'about';
            if (!in_array($page, $this->pages)) {
                $page = 'about';
            }
            $this->setViewTemplate($page.'.tpl');
        }
        return $this->generateView();
    }
}
