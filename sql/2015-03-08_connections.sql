-- --------------------------------------------------------

--
-- Table structure for table 'connections'
--

CREATE TABLE connections (
  user_id int(11) NOT NULL COMMENT 'User ID.',
  object_id int(11) NOT NULL COMMENT 'Product, role, maker ID.',
  object_type varchar(20) NOT NULL COMMENT 'Type of affected object (maker, role, product).',
  creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Creation time of the connection.',
  UNIQUE KEY user_object (user_id,object_id,object_type),
  KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User connections to makers, products, roles.';
