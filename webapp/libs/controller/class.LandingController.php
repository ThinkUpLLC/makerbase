<?php

class LandingController extends Controller {

    public function control() {
        $this->setViewTemplate('landing.tpl');

        $maker_dao = new MakerMySQLDAO();
        $makers = $maker_dao->listMakers(100);
        $this->addToView('makers', $makers);

        $product_dao = new ProductMySQLDAO();
        $products = $product_dao->listProducts(100);
        $this->addToView('products', $products);

        return $this->generateView();
    }
}
