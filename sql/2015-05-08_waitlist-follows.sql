
CREATE TABLE waitlist_follows (
  user_id varchar(30) NOT NULL COMMENT 'User ID on a particular service who has been followed.',
  follower_id varchar(30) NOT NULL COMMENT 'User ID on a particular service who has followed user_id.',
  network varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  UNIQUE KEY network_follower_user (network,follower_id,user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Waitlister followers.';
