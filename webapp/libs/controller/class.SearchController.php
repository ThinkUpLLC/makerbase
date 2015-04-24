<?php

class SearchController extends MakerbaseAuthController {

    public function control() {
        parent::control();
        $this->setViewTemplate('search.tpl');
        $this->disableCaching();

        if (isset($_GET['q'])) {
            $start_time = microtime(true);
            $client = new Elasticsearch\Client();

            $search_params = array();
            $search_params['index'] = 'maker_product_index';
            $search_params['type']  = 'maker_product_type';
            $search_params['body']['query']['multi_match']['fields'] = array('slug', 'name', 'description', 'url');
            $search_params['body']['query']['multi_match']['query'] = urlencode($_GET['q']);
            $search_params['body']['query']['multi_match']['type'] = 'phrase_prefix';

            $return_document = $client->search($search_params);

            $this->addToView('return_document', $return_document);
            $this->addToView('query', $_GET['q']);

            $end_time = microtime(true);

            if (Profiler::isEnabled()) {
                $total_time = $end_time - $start_time;
                $profiler = Profiler::getInstance();
                $profiler->add($total_time, "Elasticsearch", false);
            }

            $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
            $this->addToView('image_proxy_sig', $image_proxy_sig);
        }
        return $this->generateView();
    }
}
