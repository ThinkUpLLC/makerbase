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

# Create elasticsearch config directory
mkdir /usr/share/elasticsearch/config

# Start Elasticsearch
./bin/elasticsearch -d

# Install PHPUnit
apt-get -q -y install phpunit

# Give Elasticsearch 10 seconds to start up
sleep 10

# Copy Isosceles config file
cp /var/www/puphpet/files/makerbase-setup/configs/isosceles.config.inc.php /var/www/webapp/extlibs/isosceles/libs/config.inc.php

# Copy Makerbase config file
cp /var/www/webapp/config.sample.inc.php /var/www/webapp/config.inc.php

# Make data dir and set perms
mkdir /home/vagrant/data

# Make image-cache
mkdir /home/vagrant/data/image-cache/

# Copy over blank avatars
cp /var/www/webapp/assets/img/blank-maker.png /home/vagrant/data/image-cache/.
cp /var/www/webapp/assets/img/blank-project.png /home/vagrant/data/image-cache/.

# Set web-accessible perms
chmod -R 777 /home/vagrant/data/

# Load sample data
unzip -o /var/www/puphpet/files/makerbase-setup/sample-data/makerbase_web.sql.zip -d /var/www/puphpet/files/makerbase-setup/sample-data/
mysql -u makerbase -pnice2bnice -D makerbase_web < /var/www/puphpet/files/makerbase-setup/sample-data/makerbase_web.sql
rm -f /var/www/puphpet/files/makerbase-setup/sample-data/makerbase_web.sql

# Create makers index in Elasticsearch
curl -XPUT 'localhost:9200/_river/my_jdbc_river_makers/_meta' -d '{
    "type" : "jdbc",
    "jdbc" : {
        "url" : "jdbc:mysql://localhost:3306/makerbase_web",
        "user" : "makerbase",
        "password" : "nice2bnice",
        "sql" : "SELECT uid AS _id, uid, slug, name, url, avatar_url FROM makers",
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
        "sql" : "SELECT uid AS _id, uid, slug, name, description, url, avatar_url FROM products",
        "index" : "product_index",
        "type" : "product_type"
    }
}'

# Create tmp table
mysql -u makerbase -pnice2bnice -D makerbase_web -e "CREATE TABLE tmp SELECT CONCAT('m/', uid) as _id, uid, slug, name, '' as description, url, avatar_url, 'm' AS type FROM makers WHERE is_archived = 0 UNION SELECT CONCAT('p/', uid) as _id, uid, slug, name, description, url, avatar_url, 'p' AS type FROM products  WHERE is_archived = 0;"

# Create makers AND products index in Elasticsearch
curl -XPUT 'localhost:9200/_river/my_jdbc_river_makers_and_products/_meta' -d '{
    "type" : "jdbc",
    "jdbc" : {
        "url" : "jdbc:mysql://localhost:3306/makerbase_web",
        "user" : "makerbase",
        "password" : "nice2bnice",
        "sql" : "SELECT * FROM tmp",
        "index" : "maker_product_index",
        "type" : "maker_product_type"
    }
}'

# Drop tmp table / Apparently dropping too quickly prevents ES access to it, leave around
# mysql -u makerbase -pnice2bnice -D makerbase_web -e "DROP TABLE tmp"

