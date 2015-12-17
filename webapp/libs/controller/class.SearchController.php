<?php

class SearchController extends MakerbaseController {

    public function control() {
        parent::control();
        $this->setViewTemplate('search.tpl');
        $this->disableCaching();

        if (isset($_GET['q'])) {
            $query = $_GET['q'];
            if (substr( $query, 0, 1 ) === "@") {
                $query = substr($query, 1);
            }
            $search_params = array();
            $search_type = 'm';
            if ( isset($_GET['type'])) {
                if ($_GET['type'] == 'product' || $_GET['type'] == 'project') {
                    $search_params['index'] = 'product_index';
                    $search_params['type']  = 'product_type';
                    $search_type = 'p';
                } elseif ($_GET['type'] == 'maker') {
                    $search_params['index'] = 'maker_index';
                    $search_params['type']  = 'maker_type';
                    $search_type = 'm';
                }
            } else {
                $search_params['index'] = 'maker_product_index';
                $search_params['type']  = 'maker_product_type';
            }

            $start_time = microtime(true);
            $client = new Elasticsearch\Client();

            $search_params['body']['query']['multi_match']['fields'] = array('slug', 'name', 'url');
            $search_params['body']['query']['multi_match']['query'] = urlencode($query);
            $search_params['body']['query']['multi_match']['type'] = 'phrase_prefix';

            $return_document = $client->search($search_params);

            $results = array();
            if (isset($return_document['hits']['hits'])) {
                $image_proxy_sig = Config::getInstance()->getValue('image_proxy_sig');
                foreach ($return_document['hits']['hits'] as $hit) {
                    if (isset($hit['_source']['type'])) {
                        $search_type = $hit['_source']['type'];
                    } else {
                        $hit['_source']['type'] = $search_type;
                    }
                    $hit['_source']['avatar_url'] = $this->sendImageThruProxy($hit['_source']['avatar_url'],
                        $search_type, $image_proxy_sig);
                    $results[] = $hit['_source'];
                }
            }

            $this->addToView('return_document', $return_document);
            $this->addToView('results', $results);
            $this->addToView('query', $_GET['q']);
            if (isset($_GET['type']) && ($_GET['type'] == 'maker' || $_GET['type'] == 'product')) {
                $this->addToView('search_type', $_GET['type']);
            }

            $end_time = microtime(true);

            if (Profiler::isEnabled()) {
                $total_time = $end_time - $start_time;
                $profiler = Profiler::getInstance();
                $profiler->add($total_time, "Elasticsearch", false);
            }

            if (count($results) == 1) {
                $this->redirect('/'.$results[0]['type'].'/'.$results[0]['uid'].'/'.$results[0]['slug']);
            }
        }
        return $this->generateView();
    }
}
