<?php

class SearchHelper {
    public static function indexProduct(Product $product) {
        $client = new Elasticsearch\Client();
        $params = array();
        $params['body']  = self::getProductSearchIndexArray($product);
        $params['id'] = 'p/'.$product->uid;
        $params['index'] = 'maker_product_index';
        $params['type']  = 'maker_product_type';
        $ret = $client->index($params);
        // if ($ret['created'] != 1) {
        //     $controller->addErrorMessage('Problem adding '.$product->slug.' to search index.');
        // }
        $params['id'] = $product->uid;
        $params['index'] = 'product_index';
        $params['type']  = 'product_type';
        $ret = $client->index($params);
        // if ($ret['created'] != 1) {
        //     $controller->addErrorMessage('Problem adding '.$product->slug.' to search index.');
        // }
    }

    public static function indexMaker(Maker $maker) {
        $client = new Elasticsearch\Client();
        $params = array();
        $params['body']  = self::getMakerSearchIndexArray($maker);
        $params['id'] = 'm/'.$maker->uid;
        $params['index'] = 'maker_product_index';
        $params['type']  = 'maker_product_type';
        $ret = $client->index($params);
        // if ($ret['created'] != 1) {
        //     $controller->addErrorMessage('Problem adding '.$maker->slug.' to search index.');
        // }
        $params['id'] = $maker->uid;
        $params['index'] = 'maker_index';
        $params['type']  = 'maker_type';
        $ret = $client->index($params);
        // if ($ret['created'] != 1) {
        //     $controller->addErrorMessage('Problem adding '.$product->slug.' to search index.');
        // }
    }

    public static function getProductSearchIndexArray(Product $product) {
        return array(
            'uid'=>$product->uid,
            'slug'=>$product->slug,
            'name'=>$product->name,
            'description'=>$product->description,
            'url'=>$product->url,
            'avatar_url'=>$product->avatar_url,
            'type'=>'p'
        );
    }

    public static function getMakerSearchIndexArray(Maker $maker) {
        return array(
            'uid'=>$maker->uid,
            'slug'=>$maker->slug,
            'name'=>$maker->name,
            'description'=>'',
            'url'=>$maker->url,
            'avatar_url'=>$maker->avatar_url,
            'type'=>'m'
        );
    }

    public static function deindexProduct(Product $product) {
        $client = new Elasticsearch\Client();
        $params = array();
        $params['index'] = 'maker_product_index';
        $params['type']  = 'maker_product_type';
        $params['id'] = 'p/'.$product->uid;
        $ret = $client->delete($params);
        //print_r($ret);
        // if ($ret['found'] != 1) {
        //     $controller->addErrorMessage('Problem removing '.$product->slug.' from search index.');
        // }
        $params['index'] = 'product_index';
        $params['type']  = 'product_type';
        $params['id'] = $product->uid;
        $ret = $client->delete($params);
        //print_r($ret);
        // if ($ret['found'] != 1) {
        //     $controller->addErrorMessage('Problem removing '.$product->slug.' from products index.');
        // }
    }

    public static function deindexMaker(Maker $maker) {
        $client = new Elasticsearch\Client();
        $params = array();
        $params['index'] = 'maker_product_index';
        $params['type']  = 'maker_product_type';
        $params['id'] = 'm/'.$maker->uid;
        $ret = $client->delete($params);
        //print_r($ret);
        // if ($ret['found'] != 1) {
        //     $controller->addErrorMessage('Problem removing '.$maker->slug.' from search index.');
        // }
        $params['index'] = 'maker_index';
        $params['type']  = 'maker_type';
        $params['id'] = $maker->uid;
        $ret = $client->delete($params);
        //print_r($ret);
        // if ($ret['found'] != 1) {
        //     $controller->addErrorMessage('Problem removing '.$product->slug.' from products index.');
        // }
    }

    public static function updateIndexProduct(Product $product) {
        $client = new Elasticsearch\Client();
        $params = array();
        $params['body']['doc']  = self::getProductSearchIndexArray($product);
        $params['id'] = 'p/'.$product->uid;
        $params['index'] = 'maker_product_index';
        $params['type']  = 'maker_product_type';
        $ret = $client->update($params);
        // if ($ret['created'] != 1) {
        //     $controller->addErrorMessage('Problem adding '.$product->slug.' to search index.');
        // }
        $params['id'] = $product->uid;
        $params['index'] = 'product_index';
        $params['type']  = 'product_type';
        $ret = $client->update($params);
        // if ($ret['created'] != 1) {
        //     $controller->addErrorMessage('Problem adding '.$product->slug.' to search index.');
        // }
    }

    public static function updateIndexMaker(Maker $maker) {
        $client = new Elasticsearch\Client();
        $params = array();
        $params['body']['doc']  = self::getMakerSearchIndexArray($maker);
        $params['id'] = 'm/'.$maker->uid;
        $params['index'] = 'maker_product_index';
        $params['type']  = 'maker_product_type';
        $ret = $client->update($params);
        // if ($ret['created'] != 1) {
        //     $controller->addErrorMessage('Problem adding '.$maker->slug.' to search index.');
        // }
        $params['id'] = $maker->uid;
        $params['index'] = 'maker_index';
        $params['type']  = 'maker_type';
        $ret = $client->update($params);
        // if ($ret['created'] != 1) {
        //     $controller->addErrorMessage('Problem adding '.$product->slug.' to search index.');
        // }
    }
}