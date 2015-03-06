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
