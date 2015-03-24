<?php

class ProductController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('product.tpl');
        $product_dao = new ProductMySQLDAO();

        if (isset($_GET['clear_cache'])) {
            $this->view_mgr->clearCache('product.tpl');
        }

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

            // Show any cached user messages
            $success_message = SessionCache::get('success_message');
            if (isset($success_message)) {
                SessionCache::put('success_message', null);
                $this->addSuccessMessage($success_message);
            }
            $error_message = SessionCache::get('error_message');
            if (isset($error_message)) {
                SessionCache::put('error_message', null);
                $this->addErrorMessage($error_message);
            }

            $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
            $this->addToView('image_proxy_sig', $image_proxy_sig);
        } catch (ProductDoesNotExistException $e) {
            $this->addErrorMessage("Product ".$_GET['uid']." does not exist");
        }
        return $this->generateView();
    }
}
