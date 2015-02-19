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

### Install JDBC plugin for Elasticsearch

cd /usr/share/elasticsearch

sudo ./bin/plugin --install jdbc --url http://xbib.org/repository/org/xbib/elasticsearch/plugin/elasticsearch-river-jdbc/1.4.0.9/elasticsearch-river-jdbc-1.4.0.9-plugin.zip

sudo curl -o mysql-connector-java-5.1.33.zip -L 'http://dev.mysql.com/get/Downloads/Connector-J/mysql-connector-java-5.1.33.zip/from/http://cdn.mysql.com/'

sudo unzip mysql-connector-java-5.1.33.zip

sudo cp mysql-connector-java-5.1.33/mysql-connector-java-5.1.33-bin.jar plugins/jdbc/

sudo chmod 664 plugins/jdbc/*

sudo /etc/init.d/elasticsearch restart


#### Test JDBC plugin for Elasticsearch

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

curl 'localhost:9200/maker_index/_search?pretty'

curl 'localhost:9200/product_index/_search?pretty'

curl 'localhost:9200/_cat/indices?v'

curl -XDELETE 'localhost:9200/_river/my_jdbc_river/'