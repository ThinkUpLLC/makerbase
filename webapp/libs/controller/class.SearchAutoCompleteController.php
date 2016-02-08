<?php

class SearchAutoCompleteController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setUpJSONResponse();
        $this->disableCaching();
        $results = array();

        if (isset($_GET['q'])) {
            $query = $_GET['q'];
            if (substr( $query, 0, 1 ) === "@") {
                $query = substr($query, 1);
            }
            $start_time = microtime(true);
            $client = new Elasticsearch\Client();

            $search_params = array();
            $search_autocomplete_type = 'p';
            if ( isset($_GET['type'])) {
                if ($_GET['type'] == 'product') {
                    $search_params['index'] = 'product_index';
                    $search_params['type']  = 'product_type';
                    $search_autocomplete_type = 'p';
                } elseif ($_GET['type'] == 'maker') {
                    $search_params['index'] = 'maker_index';
                    $search_params['type']  = 'maker_type';
                    $search_autocomplete_type = 'm';
                }
            } else {
                $search_params['index'] = 'maker_product_index';
                $search_params['type']  = 'maker_product_type';
            }
            $search_params['body']['query']['multi_match']['fields'] = array('slug', 'name', 'url');
            $search_params['body']['query']['multi_match']['query'] = urlencode($query);
            $search_params['body']['query']['multi_match']['type'] = 'phrase_prefix';

            $return_document = $client->search($search_params);

            $end_time = microtime(true);

            if (Profiler::isEnabled()) {
                $total_time = $end_time - $start_time;
                $profiler = Profiler::getInstance();
                $profiler->add($total_time, "Elasticsearch", false);
            }
            if (isset($return_document['hits']['hits'])) {
                $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
                foreach ($return_document['hits']['hits'] as $hit) {
                    if (isset($hit['_source']['type'])) {
                        $search_autocomplete_type = $hit['_source']['type'];
                    }
                    $hit['_source']['avatar_url'] = $this->sendImageThruProxy($hit['_source']['avatar_url'],
                        $search_autocomplete_type, $image_proxy_sig);
                    $hit['_source']['name'] = htmlentities($hit['_source']['name']);
                    $results[] = $hit['_source'];
                }
            }
        }
        $this->setJsonData($results);
        return $this->generateView();
    }
}
