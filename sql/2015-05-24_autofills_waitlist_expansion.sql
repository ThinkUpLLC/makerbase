ALTER TABLE autofills ADD network_username VARCHAR(255) NULL DEFAULT NULL COMMENT 'Network username.' , ADD creation_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of autofill.' , ADD maker_id INT(11) NULL DEFAULT NULL COMMENT 'Maker ID.' , ADD product_id INT(11) NULL DEFAULT NULL COMMENT 'Product ID.' , ADD INDEX (maker_id) ;

UPDATE autofills SET creation_time = NOW();

ALTER TABLE waitlist ADD creation_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of waitlist addition.' ;

UPDATE waitlist SET creation_time = NOW();