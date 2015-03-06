-- --------------------------------------------------------

--
-- Table structure for table 'actions'
--

CREATE TABLE actions (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  time_performed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time action was performed.',
  user_id int(11) NOT NULL COMMENT 'ID of user who performed action.',
  ip_address varchar(255) NOT NULL COMMENT 'IP address of client where action was performed.',
  action_type varchar(50) NOT NULL COMMENT 'Type of action (create, update, archive, etc).',
  severity int(11) NOT NULL COMMENT 'Action severity (higher more severe).',
  object_id int(11) NOT NULL COMMENT 'ID of affected object.',
  object_type varchar(20) NOT NULL COMMENT 'Type of affected object (maker, role, product).',
  PRIMARY KEY (id),
  UNIQUE KEY id (id),
  KEY time_performed (time_performed,user_id,action_type)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Actions performed by users on objects.';
