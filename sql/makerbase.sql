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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `name` varchar(255) NOT NULL COMMENT 'Full name.',
  url varchar(255) NOT NULL COMMENT 'Web site URL.',
  avatar_url varchar(255) NOT NULL COMMENT 'Avatar URL.',
  twitter_user_id varchar(255) NOT NULL COMMENT 'Twitter user ID.',
  twitter_username varchar(255) NOT NULL COMMENT 'Twitter username.',
  last_login timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last login time.',
  twitter_oauth_access_token varchar(255) NOT NULL COMMENT 'Twitter OAuth token.',
  twitter_oauth_access_token_secret varchar(255) NOT NULL COMMENT 'Twitter OAuth secret.',
  maker_id int(11) DEFAULT NULL COMMENT 'Maker ID if claimed.',
  PRIMARY KEY (id),
  UNIQUE KEY twitter_user_id (twitter_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Makerbase users.';
