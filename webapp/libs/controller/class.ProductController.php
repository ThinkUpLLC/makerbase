<?php

class ProductController extends Controller {

    public function control() {
        $this->setViewTemplate('product.tpl');
        $product_dao = new ProductMySQLDAO();
        $product = $product_dao->get($_GET['slug']);
        $this->addToView('product', $product);
        return $this->generateView();
    }
}
