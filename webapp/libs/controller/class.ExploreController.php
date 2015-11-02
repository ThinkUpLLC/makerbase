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

        // Explore is expensive to render query-wise but contains no personal information
        // so set things up so it looks like you're not logged in every time
        $this->logged_in_user = null;
        $this->addToView('logged_in_user', null);

        $this->setViewTemplate('explore.tpl');

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

            //Trending inspirations
            $inspiration_dao = new InspirationMySQLDAO();
            $trending_inspirations = $inspiration_dao->getTrendingInspirations();
            $this->addToView('trending_inspirations', $trending_inspirations);

            //New inspirations
            $newest_inspirations = $inspiration_dao->getNewestInspirations();
            $this->addToView('newest_inspirations', $newest_inspirations);

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

    /**
     * Override this method to exclude logged-in user key so that the cache is universal, logged in or out.
     * @return str
     */
    public function getCacheKeyString() {
        $view_cache_key = array();
        $keys = array_keys($_GET);
        foreach ($keys as $key) {
            array_push($view_cache_key, $_GET[$key]);
        }
        return '.ht'.$this->view_template.self::KEY_SEPARATOR.(implode($view_cache_key, self::KEY_SEPARATOR));
    }
}
