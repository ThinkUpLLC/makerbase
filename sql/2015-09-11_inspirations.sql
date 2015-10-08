

CREATE TABLE inspirations (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  uid varchar(8) NOT NULL COMMENT 'External unique ID.',
  maker_id int(11) NOT NULL COMMENT 'Maker ID.',
  inspirer_maker_id int(11) NOT NULL COMMENT 'Maker who inspired.',
  description varchar(255) NOT NULL COMMENT 'Inspiration description.',
  is_shown_on_inspirer int(1) NOT NULL DEFAULT '1' COMMENT 'Whether or not inspiration is shown on inspirer page.',
  creation_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Creation time of the inspiration.',
  PRIMARY KEY (id),
  UNIQUE KEY uid (uid),
  KEY creation_time (creation_time),
  UNIQUE KEY maker_id (maker_id, inspirer_maker_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Makers who inspire other makers.';

