# Copy Isosceles config file
cp /var/www/puphpet/files/makerbase-setup/configs/config.inc.php /var/www/webapp/extlibs/isosceles/libs/config.inc.php

# Make data dir and set perms
mkdir /home/vagrant/data

chown -R www-data /home/vagrant/data/

# Load sample data
mysql -u makerbase -pnice2bnice -D makerbase_web < /var/www/sql/sample-data/sample-data.sql

# Set up Elasticsearch
# Switch to home dir
cd /usr/share/elasticsearch

# Install Elasticsearch to JDBC plugin
./bin/plugin --install jdbc --url http://xbib.org/repository/org/xbib/elasticsearch/plugin/elasticsearch-river-jdbc/1.4.0.10/elasticsearch-river-jdbc-1.4.0.10.zip

# Install MySQL JDBC connector
curl -o mysql-connector-java-5.1.33.zip -L 'http://dev.mysql.com/get/Downloads/Connector-J/mysql-connector-java-5.1.33.zip/from/http://cdn.mysql.com/'

unzip mysql-connector-java-5.1.33.zip

cp mysql-connector-java-5.1.33/mysql-connector-java-5.1.33-bin.jar plugins/jdbc/

chmod 664 plugins/jdbc/*

# Restart Elasticsearch
/etc/init.d/elasticsearch restart

# Give Elasticsearch 5 seconds to start up
sleep 5

# Create makers index in Elasticsearch
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


# Create products index in Elasticsearch
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

