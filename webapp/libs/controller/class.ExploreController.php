<?php

class ExploreController extends MakerbaseController {
    /**
     * How many user/maker/projects to show in each section
     * @var integer
     */
    var $total_list_items = 4;
    /**
     * How many days a maker/project/user had actions.
     * @var integer
     */
    var $trending_number_days = 3;

    public function control() {
        parent::control();
        $this->setViewTemplate('discover.tpl');

        if ($this->shouldRefreshCache() ) {
            //Trending makers
            $maker_dao = new MakerMySQLDAO();
            $trending_makers = $maker_dao->getTrendingMakers($this->trending_number_days, $this->total_list_items);
            $this->addToView('trending_makers', $trending_makers);

            //Trending products
            $product_dao = new ProductMySQLDAO();
            $trending_products =
                $product_dao->getTrendingProducts($this->trending_number_days, $this->total_list_items);
            $this->addToView('trending_products', $trending_products);

            //Trending users
            $user_dao = new UserMySQLDAO();
            $trending_users = $user_dao->getTrendingUsers($this->trending_number_days, $this->total_list_items);
            $this->addToView('trending_users', $trending_users);

            //New makers
            $newest_makers = $maker_dao->getNewestMakers($this->total_list_items);
            $this->addToView('newest_makers', $newest_makers);

            //New products
            $newest_products = $product_dao->getNewestProducts($this->total_list_items);
            $this->addToView('newest_products', $newest_products);

            //New users
            $newest_users = $user_dao->getNewestUsers($this->total_list_items);
            $this->addToView('newest_users', $newest_users);

            //Featured makers
            $config = Config::getInstance();
            $featured_makers = $config->getValue('featured_makers');
            $this->addToView('featured_makers', $featured_makers);

            //Featured products
            $featured_products = $config->getValue('featured_products');
            $this->addToView('featured_products', $featured_products);

            //Featured users
            $featured_user_uids = $config->getValue('featured_users');
            $user_dao = new UserMySQLDAO();
            $featured_users = array();
            //@TODO Optimize this!
            foreach($featured_user_uids as $featured_user_uid) {
                $featured_users[] = $user_dao->get($featured_user_uid);
            }
            $this->addToView('featured_users', $featured_users);
        }
        return $this->generateView();
    }
}