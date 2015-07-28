# Makerbase

A directory of people who make things

## Requirements

* [Vagrant](https://vagrantup.com)
* [VirtualBox](https://www.virtualbox.org/)
* ```vagrant plugin install vagrant-bindfs```

## Install

Clone the repository:

    $ git clone git@github.com:ThinkUpLLC/makerbase.git

Get required submodules:

    $ cd makerbase; git submodule init; git submodule update --recursive

## Run

Spin up virtual machine: (first run takes awhile)

    $ vagrant up

All done? Congratulations!

## Use

See Makerbase in your browser:

* http://makerbase.dev/

Note: If makerbase.dev doesn't resolve, make sure the following line is in your host computer's /etc/hosts file:

    192.168.56.101 default makerbase.dev www.makerbase.dev

Use the code editor and git client of your choice on your host machine. Edit files in the makerbase directory.

### Offline Sign-in

For offline development when you don't have access to Twitter.com, go to makerbase.dev and add fl=[makerbase_user_uid] to the URL to "sign in" as that user.

For example, to fake a login as @makerbase, go to http://makerbase.dev/?fl=48cmyk

### Run the tests

SSH into the guest machine to run the tests.

    [host]  $ vagrant ssh
    [guest] $ cd /var/www/; sudo phpunit tests

## Tools

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

If you ``vagrant destroy`` then ``vagrant up`` again and keep getting Authentication failures, run:

    $ rm puphpet/files/dot/ssh/id_rsa

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

## Update Isosceles

    $ cd isosceles; git pull origin master; cd ..
    $ git add isosceles; git commit -m "Isosceles submodule updated"
