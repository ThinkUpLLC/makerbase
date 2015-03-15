<?php

class ActionController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('action.tpl');
        $action_dao = new ActionMySQLDAO();

        try {
            $action = $action_dao->get($_GET['uid']);

            $this->addToView('action', $action);

            $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
            $this->addToView('image_proxy_sig', $image_proxy_sig);
        } catch (ActionDoesNotExistException $e) {
            $this->addErrorMessage("Action ".$_GET['uid']." does not exist");
        }
        return $this->generateView();
    }
}
