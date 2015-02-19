<?php
require 'vendor/autoload.php';

if (isset($_GET['q'])) {
    $client = new Elasticsearch\Client();

    $type = (isset($_GET['type'])?$_GET['type']:'maker');

    if ($type=='maker') {
        $search_params = array();
        $search_params['index'] = 'maker_index';
        $search_params['type']  = 'maker_type';
        $search_params['body']['query']['match_phrase']['name'] = $_GET['q'];
        $return_document = $client->search($search_params);
    }

    if ($type=='product') {
        $search_params = array();
        $search_params['index'] = 'product_index';
        $search_params['type']  = 'product_type';
        $search_params['body']['query']['bool']['should'] = array(
            array('match' => array('name' => $_GET['q'])),
            array('match' => array('description' => $_GET['q'])),
        );

        $return_document = $client->search($search_params);
    }

    if (isset($return_document)) {
        echo "<pre>";
        print_r($return_document);
        echo "</pre>";
    }
} else {
    echo "Hello. Search for something. <form><input type='text' name='q'> <input type='Submit' value='Search'></form>";
}