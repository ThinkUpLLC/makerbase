ALTER TABLE actions ADD uid VARCHAR(8) NOT NULL COMMENT 'External unique ID.' AFTER id, ADD UNIQUE (uid) ;
