CREATE TABLE sent_tweets (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique id.',
  twitter_username varchar(255) NOT NULL COMMENT 'Twitter name.',
  twitter_user_id varchar(100) NOT NULL COMMENT 'Twitter ID.',
  sent_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time tweet was sent.',
  PRIMARY KEY (id),
  UNIQUE KEY twitter_user_id (twitter_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tweets sent to Makerbase makers.';
