<?php

class LandingController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('landing.tpl');

        $maker_dao = new MakerMySQLDAO();
        $makers = $maker_dao->listMakers(100);
        $this->addToView('makers', $makers);

        $product_dao = new ProductMySQLDAO();
        $products = $product_dao->listProducts(100);
        $this->addToView('products', $products);

        $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
        $this->addToView('image_proxy_sig', $image_proxy_sig);

        return $this->generateView();
    }
}
