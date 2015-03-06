ALTER TABLE makers ADD creation_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time.' , ADD INDEX (creation_time) ;

ALTER TABLE products ADD creation_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time.' , ADD INDEX (creation_time) ;

ALTER TABLE roles ADD creation_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time.' , ADD INDEX (creation_time) ;

ALTER TABLE users ADD creation_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time.' AFTER twitter_username, ADD INDEX (creation_time) ;