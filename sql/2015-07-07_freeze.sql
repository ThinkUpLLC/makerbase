ALTER TABLE makers ADD is_frozen INT(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not object is frozen (locked from changes).' AFTER is_archived;

ALTER TABLE products ADD is_frozen INT(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not object is frozen (locked from changes).' AFTER is_archived;

ALTER TABLE users ADD is_frozen INT(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not object is frozen (locked from changes).' AFTER maker_id;

ALTER TABLE actions ADD is_admin INT(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not this is an admin action.' AFTER metadata, ADD INDEX (is_admin);