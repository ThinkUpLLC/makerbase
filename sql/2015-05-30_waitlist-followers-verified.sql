ALTER TABLE waitlist ADD follower_count INT(11) NOT NULL DEFAULT '0' COMMENT 'Total followers on network.' AFTER network_username, ADD is_verified INT(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not user is verified on network.' AFTER follower_count, ADD INDEX (follower_count), ADD INDEX (is_verified) ;