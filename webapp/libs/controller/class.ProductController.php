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
                $page_number = (isset($_GET['p']))?$_GET['p']:1;
                $limit = 10;

                $action_dao = new ActionMySQLDAO();
                $actions = $action_dao->getActivitiesPerformedOnProduct($product, $page_number, $limit);

                if (count($actions) > $limit) {
                    array_pop($actions);
                    $this->addToView('next_page', $page_number+1);
                }
                if ($page_number > 1) {
                    $this->addToView('prev_page', $page_number-1);
                }
                $this->addToView('actions', $actions);

                // Transfer cached user messages to the view
                $this->setUserMessages();

                $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
                $this->addToView('image_proxy_sig', $image_proxy_sig);

                $this->addToView('placeholder', Role::getRandoPlaceholder());
            } catch (ProductDoesNotExistException $e) {
                $this->redirect('/404');
            }
        }
        return $this->generateView();
    }
}
