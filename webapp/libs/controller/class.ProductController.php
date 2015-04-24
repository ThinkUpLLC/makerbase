<?php

class ProductController extends MakerbaseAuthController {

    public function control() {
        parent::control();
        $this->setViewTemplate('product.tpl');

        if ($this->shouldRefreshCache() ) {
            $product_dao = new ProductMySQLDAO();

            try {
                $product = $product_dao->get($_GET['uid']);

                $this->addToView('product', $product);

                $role_dao = new RoleMySQLDAO();
                $roles = $role_dao->getByProduct($product->id);
                $this->addToView('roles', $roles);

                // Get actions
                $action_dao = new ActionMySQLDAO();
                $actions = $action_dao->getActivitiesPerformedOnProduct($product);
                $this->addToView('actions', $actions);

                // Transfer cached user messages to the view
                $this->setUserMessages();

                $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
                $this->addToView('image_proxy_sig', $image_proxy_sig);
            } catch (ProductDoesNotExistException $e) {
                $this->addErrorMessage("Product ".$_GET['uid']." does not exist");
            }
        }
        return $this->generateView();
    }
}
