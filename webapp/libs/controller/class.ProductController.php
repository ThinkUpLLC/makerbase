<?php

class ProductController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('product.tpl');
        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->get($_GET['slug']);
        $this->addToView('product', $product);

        $role_dao = new RoleMySQLDAO();
        $roles = $role_dao->getByProduct($product->id);
        $this->addToView('roles', $roles);

        // Get actions
        $action_dao = new ActionMySQLDAO();
        $actions = $action_dao->getActivitiesPerformedOnProduct($product);
        $this->addToView('actions', $actions);

        $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
        $this->addToView('image_proxy_sig', $image_proxy_sig);

        return $this->generateView();
    }
}
