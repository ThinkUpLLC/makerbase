<?php
require 'vendor/autoload.php';

if (isset($_GET['q'])) {
    $client = new Elasticsearch\Client();

    $search_params = array();
    $search_params['index'] = 'maker_product_index';
    $search_params['type']  = 'maker_product_type';
    $search_params['body']['query']['multi_match']['fields'] = array('slug', 'name', 'description', 'url');
    $search_params['body']['query']['multi_match']['query'] = urlencode($_GET['q']);
    $search_params['body']['query']['multi_match']['type'] = 'phrase_prefix';

    $return_document = $client->search($search_params);

    if (isset($return_document)) {
        echo "<pre>";
        print_r($return_document);
        echo "</pre>";
    }
} else {
    echo "Hello. Search for something. <form><input type='text' name='q'> <input type='Submit' value='Search'></form>";
}