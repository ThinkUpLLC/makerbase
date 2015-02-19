# Makerbase

A directory of people who make things

## Requirements

* [Vagrant](https://vagrantup.com)
* [VirtualBox](https://www.virtualbox.org/)
* ```vagrant plugin install vagrant-bindfs```

## Install

$ git clone git@github.com:ThinkUpLLC/makerbase.git

$ cd makerbase

$ git submodule init

$ git pull --recurse-submodules

$ git submodule update --recursive

## Usage

Spin up virtual machine: (Note: first run takes awhile)

    $ vagrant up

See Makerbase in your browser:

* http://makerbase.dev/

Note: If makerbase.dev doesn't resolve, make sure the following line is in your host computer's /etc/hosts file:

    192.168.56.101 default makerbase.dev www.makerbase.dev

Adminer database admin:

* http://192.168.56.101/adminer/
* makerbase / nice2bnice

MailCatcher

* http://192.168.56.101:1080/

SSH in:

    $ vagrant ssh

Destroy virtual machine:

    $ vagrant destroy

Note:  This does not delete setup files or the contents of the default directory.

## Modify

This Vagrant virtual machine was built with [PuPHPet](http://puphpet.com). To modify it for your own purposes, drag and drop puphpet/config.yaml onto (http://puphpet.com) and regenerate.


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
