ALTER TABLE users
ADD is_subscribed_maker_change_email INT(1)
NOT NULL DEFAULT '1' COMMENT 'Whether or not user gets maker change email notifications.' ,
ADD last_maker_change_email_sent TIMESTAMP NULL DEFAULT
NULL COMMENT 'Last time an email notification of a maker change was sent.' ,
ADD is_subscribed_friend_activity_email INT(1)
NOT NULL DEFAULT '1' COMMENT 'Whether or not user should get friend activity email notifications.';
