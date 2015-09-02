ALTER TABLE event_makers RENAME TO event_permissions;

ALTER TABLE event_permissions CHANGE twitter_username twitter_username VARCHAR(100) NOT NULL COMMENT 'Twitter username.';

ALTER TABLE event_permissions ADD INDEX (event, twitter_username);

-- --------------------------------------------------------

--
-- Table structure for table event_makers
--

CREATE TABLE event_makers (
  event_slug varchar(10) NOT NULL COMMENT 'Event slug.',
  maker_id int(11) NOT NULL COMMENT 'Maker ID.',
  is_speaker int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not maker is a speaker.',
  speak_date date DEFAULT NULL COMMENT 'Day speaker is speaking.',
  UNIQUE KEY event_slug (event_slug,maker_id),
  KEY is_speaker (is_speaker)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Makers attending an event.';


INSERT INTO event_makers (event_slug, maker_id, is_speaker, speak_date) VALUES
('xoxo2015',    3,  0,  NULL),
('xoxo2015',    4,  0,  NULL),
('xoxo2015',    7,  0,  NULL),
('xoxo2015',    478,    1,  '2015-09-12'),
('xoxo2015',    919,    1,  '2015-09-12'),
('xoxo2015',    1114,   1,  '2015-09-12');
