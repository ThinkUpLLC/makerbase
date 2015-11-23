ALTER TABLE users
ADD is_subscribed_announcements_email INT(1)
NOT NULL DEFAULT '1' COMMENT 'Whether or not user should get Makerbase announcements via email.';