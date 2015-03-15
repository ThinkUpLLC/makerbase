# Rivers:

curl -XDELETE 'localhost:9200/_river/my_jdbc_river_makers/'
curl -XDELETE 'localhost:9200/_river/my_jdbc_river_products/'
curl -XDELETE 'localhost:9200/_river/my_jdbc_river_makers_and_products/'

# Indexes:

curl -XDELETE 'localhost:9200/maker_product_index/'
curl -XDELETE 'localhost:9200/maker_index/'
curl -XDELETE 'localhost:9200/product_index/'

# Check:

curl 'localhost:9200/_cat/indices?v'

