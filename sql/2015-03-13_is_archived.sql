ALTER TABLE makers ADD is_archived INT(1) DEFAULT 0 NOT NULL COMMENT 'Has maker been archived, 1 yes, 0 no.' , ADD INDEX (is_archived) ;

ALTER TABLE products ADD is_archived INT(1) DEFAULT 0 NOT NULL COMMENT 'Has product been archived, 1 yes, 0 no.' , ADD INDEX (is_archived) ;

ALTER TABLE roles ADD is_archived INT(1) DEFAULT 0 NOT NULL COMMENT 'Has role been archived, 1 yes, 0 no.' , ADD INDEX (is_archived) ;