<?php

class SearchAutoCompleteController extends MakerbaseAuthController {

    public function control() {
        parent::control();
        $this->disableCaching();
        $results = array();

        if (isset($_GET['q'])) {
            $start_time = microtime(true);
            $client = new Elasticsearch\Client();

            $search_params = array();
            if ( isset($_GET['type'])) {
                if ($_GET['type'] == 'product') {
                    $search_params['index'] = 'product_index';
                    $search_params['type']  = 'product_type';
                } elseif ($_GET['type'] == 'maker') {
                    $search_params['index'] = 'maker_index';
                    $search_params['type']  = 'maker_type';
                }
            } else {
                $search_params['index'] = 'maker_product_index';
                $search_params['type']  = 'maker_product_type';
            }
            $search_params['body']['query']['multi_match']['fields'] = array('slug', 'name', 'url');
            $search_params['body']['query']['multi_match']['query'] = urlencode($_GET['q']);
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
                    $hit['_source']['avatar_url'] = $this->sendImageThruProxy($hit['_source']['avatar_url'],
                        $hit['_source']['type'], $image_proxy_sig);
                    $results[] = $hit['_source'];
                }
            }
        }
        $this->setJsonData($results);
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
