--
-- Database: 'makerbase'
--

-- --------------------------------------------------------

--
-- Table structure for table 'actions'
--

CREATE TABLE actions (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  uid varchar(8) NOT NULL COMMENT 'External unique ID.',
  time_performed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time action was performed.',
  user_id int(11) NOT NULL COMMENT 'ID of user who performed action.',
  ip_address varchar(255) NOT NULL COMMENT 'IP address of client where action was performed.',
  action_type varchar(50) NOT NULL COMMENT 'Type of action (create, update, archive, etc).',
  severity int(11) NOT NULL COMMENT 'Action severity (higher more severe).',
  object_id int(11) NOT NULL COMMENT 'ID of affected object.',
  object_type varchar(20) NOT NULL COMMENT 'Type of affected object (maker, role, product).',
  object2_id int(11) DEFAULT NULL COMMENT 'ID of second affected object.',
  object2_type varchar(20) DEFAULT NULL COMMENT 'Type of second affected object.',
  metadata text COMMENT 'Object metadata.',
  PRIMARY KEY (id),
  UNIQUE KEY id (id),
  UNIQUE KEY uid (uid),
  KEY time_performed (time_performed,user_id,action_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Actions performed by users on objects.';

-- --------------------------------------------------------

--
-- Table structure for table 'autofills'
--

CREATE TABLE autofills (
  network_id varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'ID of the object on source network.',
  network varchar(25) CHARACTER SET utf8 NOT NULL COMMENT 'Source network of the autofill.',
  network_username varchar(255) DEFAULT NULL COMMENT 'Network username.',
  creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of autofill.',
  maker_id int(11) DEFAULT NULL COMMENT 'Maker ID.',
  product_id int(11) DEFAULT NULL COMMENT 'Product ID.',
  UNIQUE KEY network_id (network_id,network),
  KEY maker_id (maker_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Third-party users that autofilled makers and projects.';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='User connections to makers, products, roles.';

-- --------------------------------------------------------

--
-- Table structure for table 'makers'
--

CREATE TABLE makers (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  uid varchar(8) NOT NULL COMMENT 'External unique ID.',
  slug varchar(191) NOT NULL COMMENT 'URL slug.',
  `name` varchar(255) NOT NULL COMMENT 'Full name.',
  url varchar(255) NOT NULL COMMENT 'Web site URL.',
  avatar_url varchar(255) NOT NULL COMMENT 'Avatar URL.',
  creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time.',
  is_archived int(1) NOT NULL DEFAULT '0' COMMENT 'Has maker been archived, 1 yes, 0 no.',
  PRIMARY KEY (id),
  UNIQUE KEY uid (uid),
  KEY creation_time (creation_time),
  KEY is_archived (is_archived)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='People who make products.';

-- --------------------------------------------------------

--
-- Table structure for table 'products'
--

CREATE TABLE products (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  uid varchar(8) NOT NULL COMMENT 'External unique ID.',
  slug varchar(191) NOT NULL COMMENT 'URL slug.',
  `name` varchar(255) NOT NULL COMMENT 'Product name.',
  description text NOT NULL COMMENT 'Product description.',
  url varchar(255) NOT NULL COMMENT 'Product web site url.',
  avatar_url varchar(255) NOT NULL COMMENT 'Product avatar url.',
  creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time.',
  is_archived int(1) NOT NULL DEFAULT '0' COMMENT 'Has product been archived, 1 yes, 0 no.',
  PRIMARY KEY (id),
  UNIQUE KEY uid (uid),
  KEY creation_time (creation_time),
  KEY is_archived (is_archived)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Products made by people.';

-- --------------------------------------------------------

--
-- Table structure for table 'roles'
--

CREATE TABLE roles (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  uid varchar(8) NOT NULL COMMENT 'External unique ID.',
  product_id int(11) NOT NULL COMMENT 'Product ID.',
  maker_id int(11) NOT NULL COMMENT 'Maker ID.',
  role varchar(255) NOT NULL COMMENT 'Role of maker in product.',
  `start` date DEFAULT NULL COMMENT 'Start date.',
  `end` date DEFAULT NULL COMMENT 'End date.',
  creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time.',
  is_archived int(1) NOT NULL DEFAULT '0' COMMENT 'Has role been archived, 1 yes, 0 no.',
  PRIMARY KEY (id),
  UNIQUE KEY uid (uid),
  KEY product_id (product_id,maker_id),
  KEY creation_time (creation_time),
  KEY is_archived (is_archived)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Maker roles in products.';

-- --------------------------------------------------------

--
-- Table structure for table 'users'
--

CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  uid varchar(8) NOT NULL COMMENT 'External unique ID.',
  `name` varchar(255) NOT NULL COMMENT 'Full name.',
  url varchar(255) NOT NULL COMMENT 'Web site URL.',
  avatar_url varchar(255) NOT NULL COMMENT 'Avatar URL.',
  twitter_user_id varchar(191) NOT NULL COMMENT 'Twitter user ID.',
  twitter_username varchar(255) NOT NULL COMMENT 'Twitter username.',
  creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time.',
  last_login_time timestamp COMMENT 'Last login time.',
  twitter_oauth_access_token varchar(255) NOT NULL COMMENT 'Twitter OAuth token.',
  twitter_oauth_access_token_secret varchar(255) NOT NULL COMMENT 'Twitter OAuth secret.',
  maker_id int(11) DEFAULT NULL COMMENT 'Maker ID if claimed.',
  PRIMARY KEY (id),
  UNIQUE KEY uid (uid),
  UNIQUE KEY twitter_user_id (twitter_user_id),
  KEY creation_time (creation_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Makerbase users.';

-- --------------------------------------------------------

--
-- Table structure for table 'waitlist'
--

CREATE TABLE waitlist (
  network_id varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'User ID on the source network.',
  network varchar(25) CHARACTER SET utf8 NOT NULL COMMENT 'Network of the user attempting to sign in.',
  network_username varchar(255) NOT NULL COMMENT 'Username on source network.',
  creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of waitlist addition.',
  is_archived int(1) NOT NULL DEFAULT '0' COMMENT 'Is waitlister archived (signed up).',
  UNIQUE KEY network_id (network_id,network),
  KEY is_archived (is_archived)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Waitlisted users.';


-- Whitelist Gina and Anil for login

INSERT INTO autofills (network_id, network) VALUES ('930061', 'twitter');

INSERT INTO autofills (network_id, network) VALUES ('36823', 'twitter');