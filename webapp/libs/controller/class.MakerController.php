<?php

class MakerController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('maker.tpl');

        if ($this->shouldRefreshCache() ) {
            $maker_dao = new MakerMySQLDAO();

            try {
                $maker = $maker_dao->get($_GET['uid']);
                $this->addToView('maker', $maker);

                $role_dao = new RoleMySQLDAO();
                $roles = $role_dao->getByMaker($maker->id);
                $this->addToView('roles', $roles);

                // Get actions
                $action_dao = new ActionMySQLDAO();
                $actions = $action_dao->getActivitiesPerformedOnMaker($maker);
                $this->addToView('actions', $actions);

                // Transfer cached user messages to the view
                $this->setUserMessages();

                $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
                $this->addToView('image_proxy_sig', $image_proxy_sig);
            } catch (MakerDoesNotExistException $e) {
                $this->addErrorMessage("Maker ".$_GET['uid']." does not exist");
            }
        }
        return $this->generateView();
    }
}
