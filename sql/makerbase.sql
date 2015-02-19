--
-- Database: 'makerbase'
--

-- --------------------------------------------------------

--
-- Table structure for table 'makers'
--

CREATE TABLE makers (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  slug varchar(255) NOT NULL COMMENT 'URL slug.',
  username varchar(255) NOT NULL COMMENT 'Username.',
  `name` varchar(255) NOT NULL COMMENT 'Full name.',
  url varchar(255) NOT NULL COMMENT 'Web site URL.',
  avatar_url varchar(255) NOT NULL COMMENT 'Avatar URL.',
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='People who make products.';

-- --------------------------------------------------------

--
-- Table structure for table 'roles'
--

CREATE TABLE roles (
  product_id int(11) NOT NULL COMMENT 'Product ID.',
  maker_id int(11) NOT NULL COMMENT 'Maker ID.',
  role varchar(255) NOT NULL COMMENT 'Role of maker in product.',
  `start` date NOT NULL COMMENT 'Start date.',
  `end` date DEFAULT NULL COMMENT 'End date.',
  KEY product_id (product_id,maker_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Maker roles in products.';

-- --------------------------------------------------------

--
-- Table structure for table 'products'
--

CREATE TABLE products (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  slug varchar(255) NOT NULL COMMENT 'URL slug.',
  `name` varchar(255) NOT NULL COMMENT 'Product name.',
  description text NOT NULL COMMENT 'Product description.',
  url varchar(255) NOT NULL COMMENT 'Product web site url.',
  avatar_url varchar(255) NOT NULL COMMENT 'Product avatar url.',
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Products made by people.';
