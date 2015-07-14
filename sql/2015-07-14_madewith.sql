CREATE TABLE made_withs (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  uid varchar(8) NOT NULL COMMENT 'External unique ID.',
  product_id int(11) NOT NULL COMMENT 'Product ID.',
  used_product_id int(11) NOT NULL COMMENT 'Product ID used by product.',
  creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time.',
  is_archived int(1) NOT NULL DEFAULT '0' COMMENT 'Has use been archived, 1 yes, 0 no.',
  PRIMARY KEY (id),
  UNIQUE KEY uid (uid),
  UNIQUE KEY product_id (product_id,used_product_id),
  KEY creation_time (creation_time),
  KEY is_archived (is_archived)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Products that are made with other products.';
