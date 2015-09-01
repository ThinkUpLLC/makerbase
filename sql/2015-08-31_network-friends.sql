
CREATE TABLE network_friends (
  user_id varchar(30) NOT NULL COMMENT 'User ID on a particular service who has followed friend_id.',
  friend_id varchar(30) NOT NULL COMMENT 'User ID on a particular service who has been followed by user_id.',
  network varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'Originating network in lower case, i.e., twitter or github.',
  UNIQUE KEY network_user_friend (network,user_id,friend_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='User friends on a given network.';

DROP TABLE waitlist_follows;

ALTER TABLE users
ADD last_loaded_friends timestamp NULL DEFAULT NULL COMMENT 'Last network friends fetch time.';

ALTER TABLE makers ADD INDEX (autofill_network_id, autofill_network);
ALTER TABLE products ADD INDEX (autofill_network_id, autofill_network);