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
  is_admin int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not this is an admin action.',
  PRIMARY KEY (id),
  UNIQUE KEY id (id),
  UNIQUE KEY uid (uid),
  KEY time_performed (time_performed,user_id,action_type),
  KEY is_admin (is_admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Actions performed by users on objects.';

-- --------------------------------------------------------

--
-- Table structure for table 'action_objects'
--

CREATE TABLE action_objects (
    id INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.' ,
    action_id INT(11) NOT NULL COMMENT 'Action ID.' ,
    object_id INT(11) NOT NULL COMMENT 'Product, maker, user ID.' ,
    object_type VARCHAR(20) NOT NULL COMMENT 'Type of object (Maker, Product, User).' ,
    PRIMARY KEY (id),
    KEY object_id (object_id, object_type)
) ENGINE=InnoDB COMMENT='Objects involved in each action.';

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
-- Table structure for table 'cookies'
--

CREATE TABLE cookies (
  cookie varchar(100) not null COMMENT 'Unique cookie key.',
  user_uid varchar(200) not null COMMENT 'UID of the user logged in with this cookie.',
  unique key (cookie),
  index (user_uid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Browser cookies that maintain logged-in user sessions.';

-- --------------------------------------------------------

--
-- Table structure for table event_makers
--

CREATE TABLE event_makers (
  event_slug varchar(10) NOT NULL COMMENT 'Event slug.',
  maker_id int(11) NOT NULL COMMENT 'Maker ID.',
  is_speaker int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not maker is a speaker.',
  speak_date datetime DEFAULT NULL COMMENT 'Day speaker is speaking.',
  UNIQUE KEY event_slug (event_slug,maker_id),
  KEY is_speaker (is_speaker)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Makers attending an event.';

-- --------------------------------------------------------

--
-- Table structure for table event_permissions
--

CREATE TABLE event_permissions (
  event varchar(10) NOT NULL COMMENT 'Event slug.',
  twitter_username varchar(100) NOT NULL COMMENT 'Twitter username',
  KEY event (event,twitter_username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Makers attending an event.';

-- --------------------------------------------------------

--
-- Table structure for table 'made_withs'
--

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
  is_frozen int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not object is frozen (locked from changes).',
  autofill_network_id varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Network ID of the maker from autofill.',
  autofill_network varchar(25) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Source network of the autofill.',
  autofill_network_username varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Network username of the maker from autofill.',
  PRIMARY KEY (id),
  UNIQUE KEY uid (uid),
  KEY creation_time (creation_time),
  KEY is_archived (is_archived),
  KEY autofill_network_id (autofill_network_id,autofill_network)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='People who make products.';

-- --------------------------------------------------------

--
-- Table structure for table 'network_friends'
--

CREATE TABLE network_friends (
  user_id varchar(30) NOT NULL COMMENT 'User ID on a particular service who has followed friend_id.',
  friend_id varchar(30) NOT NULL COMMENT 'User ID on a particular service who has been followed by user_id.',
  network varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'Originating network in lower case, i.e., twitter or github.',
  UNIQUE KEY network_user_friend (network,user_id,friend_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='User friends on a given network.';

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
  is_frozen int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not object is frozen (locked from changes).',
  autofill_network_id varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Network ID of the product from autofill.',
  autofill_network varchar(25) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Source network of the autofill.',
  autofill_network_username varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Network username of the product from autofill.',
  PRIMARY KEY (id),
  UNIQUE KEY uid (uid),
  KEY creation_time (creation_time),
  KEY is_archived (is_archived),
  KEY autofill_network_id (autofill_network_id,autofill_network)
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
  url varchar(255) DEFAULT NULL COMMENT 'Web site URL.',
  avatar_url varchar(255) NOT NULL COMMENT 'Avatar URL.',
  twitter_user_id varchar(191) NOT NULL COMMENT 'Twitter user ID.',
  twitter_username varchar(255) NOT NULL COMMENT 'Twitter username.',
  creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time.',
  last_login_time timestamp COMMENT 'Last login time.',
  twitter_oauth_access_token varchar(255) NOT NULL COMMENT 'Twitter OAuth token.',
  twitter_oauth_access_token_secret varchar(255) NOT NULL COMMENT 'Twitter OAuth secret.',
  maker_id int(11) DEFAULT NULL COMMENT 'Maker ID if claimed.',
  is_frozen int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not object is frozen (locked from changes).',
  email varchar(200) DEFAULT NULL COMMENT 'User email address.',
  email_verification_code int(10) DEFAULT NULL COMMENT 'Email verification code.',
  is_email_verified int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not email is verified.',
  has_added_maker int(1) NOT NULL DEFAULT '0' COMMENT 'Whether user has added a maker.',
  has_added_product int(1) NOT NULL DEFAULT '0' COMMENT 'Whether user has added a product.',
  has_added_role int(1) NOT NULL DEFAULT '0' COMMENT 'Whether user has added a role.',
  last_loaded_friends timestamp NULL DEFAULT NULL COMMENT 'Last network friends fetch time.',
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
  follower_count int(11) NOT NULL DEFAULT '0' COMMENT 'Total followers on network.',
  is_verified int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not user is verified on network.',
  twitter_oauth_access_token varchar(255) DEFAULT NULL COMMENT 'Twitter OAuth token.',
  twitter_oauth_access_token_secret varchar(255) DEFAULT NULL COMMENT 'Twitter OAuth secret.',
  creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of waitlist addition.',
  is_archived int(1) NOT NULL DEFAULT '0' COMMENT 'Is waitlister archived (signed up).',
  UNIQUE KEY network_id (network_id,network),
  KEY is_archived (is_archived),
  KEY follower_count (follower_count),
  KEY is_verified (is_verified)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Waitlisted users.';

