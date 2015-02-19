# Makerbase

A directory of people who make things

## Elasticsearch Cheatsheet

Restart node:

$ sudo /etc/init.d/elasticsearch restart

Get settings (like home directory):

$ curl http://localhost:9200/_nodes?settings=true&pretty=true

## Libraries

JDBC plugin for Elasticsearch https://github.com/jprante/elasticsearch-river-jdbc

Elasticsearch Library for PHP (included) https://github.com/elasticsearch/elasticsearch-php

#### Create maker and product indices for Elasticsearch

curl -XPUT 'localhost:9200/_river/my_jdbc_river_makers/_meta' -d '{
    "type" : "jdbc",
    "jdbc" : {
        "url" : "jdbc:mysql://localhost:3306/makerbase_web",
        "user" : "makerbase",
        "password" : "nice2bnice",
        "sql" : "select * from makers",
        "index" : "maker_index",
        "type" : "maker_type"
    }
}'

curl -XPUT 'localhost:9200/_river/my_jdbc_river_products/_meta' -d '{
    "type" : "jdbc",
    "jdbc" : {
        "url" : "jdbc:mysql://localhost:3306/makerbase_web",
        "user" : "makerbase",
        "password" : "nice2bnice",
        "sql" : "select * from products",
        "index" : "product_index",
        "type" : "product_type"
    }
}'

### Test indices

curl 'localhost:9200/maker_index/_search?pretty'

curl 'localhost:9200/product_index/_search?pretty'

curl 'localhost:9200/_cat/indices?v'

### Delete JDBC river

curl -XDELETE 'localhost:9200/_river/my_jdbc_river/'
