<?php

class SearchController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('search.tpl');

        if (isset($_GET['q'])) {
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
        }
        return $this->generateView();
    }
}
