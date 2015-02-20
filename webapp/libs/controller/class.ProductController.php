<?php

class ProductController extends Controller {

    public function control() {
        $this->setViewTemplate('product.tpl');
        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->get($_GET['slug']);
        $this->addToView('product', $product);

        $role_dao = new RoleMySQLDAO();
        $roles = $role_dao->getByProduct($product->id);
        $this->addToView('roles', $roles);

        return $this->generateView();
    }
}
