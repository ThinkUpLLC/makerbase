ALTER TABLE makers ADD uid VARCHAR(8) NOT NULL COMMENT 'External unique ID.' AFTER id, ADD UNIQUE (uid) ;

ALTER TABLE makers DROP INDEX slug;

ALTER TABLE makers DROP username;

ALTER TABLE products ADD uid VARCHAR(8) NOT NULL COMMENT 'External unique ID.' AFTER id, ADD UNIQUE (uid) ;

ALTER TABLE products DROP INDEX slug;

ALTER TABLE roles ADD uid VARCHAR(8) NOT NULL COMMENT 'External unique ID.' AFTER id, ADD UNIQUE (uid) ;