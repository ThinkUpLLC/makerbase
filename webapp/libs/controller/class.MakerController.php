<?php

class MakerController extends Controller {

    public function control() {
        $this->setViewTemplate('maker.tpl');

        $maker_dao = new MakerMySQLDAO();
        $maker = $maker_dao->get($_GET['slug']);
        $this->addToView('maker', $maker);

        $role_dao = new RoleMySQLDAO();
        $roles = $role_dao->getByMaker($maker->id);
        $this->addToView('roles', $roles);

        return $this->generateView();
    }
}
