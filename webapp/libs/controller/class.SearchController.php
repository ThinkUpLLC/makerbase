<?php

class SearchController extends MakerbaseAuthController {

    public function control() {
        parent::control();
        $this->setViewTemplate('search.tpl');
        $this->disableCaching();

        if (isset($_GET['q'])) {
            $search_params = array();
            $search_type = 'm';
            if ( isset($_GET['type'])) {
                if ($_GET['type'] == 'product') {
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
            $search_params['body']['query']['multi_match']['query'] = urlencode($_GET['q']);
            $search_params['body']['query']['multi_match']['type'] = 'phrase_prefix';

            $return_document = $client->search($search_params);

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

    private function sendImageThruProxy($image_url, $type, $image_proxy_sig) {
        if (empty($image_url)) {
            if ($type == 'm' || $type == 'u') {
                return 'https://makerba.se/assets/img/blank-maker.png';
            } else {
                return 'https://makerba.se/assets/img/blank-project.png';
            }
        } else {
            if (!empty($image_proxy_sig)) {
                return 'https://makerba.se/img.php?url='.$image_url."&t=".$type."&s=".$image_proxy_sig;
            } else {
                return $image_url;
            }
        }
    }
}
