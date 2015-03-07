<?php

class MakerController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('maker.tpl');

        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->get($_GET['slug']);
        $this->addToView('maker', $maker);

        $role_dao = new RoleMySQLDAO();
        $roles = $role_dao->getByMaker($maker->id);
        $this->addToView('roles', $roles);

        // Get actions
        $action_dao = new ActionMySQLDAO();
        $actions = $action_dao->getActivitiesPerformedOnMaker($maker);
        $this->addToView('actions', $actions);

        $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
        $this->addToView('image_proxy_sig', $image_proxy_sig);

        return $this->generateView();
    }
}
