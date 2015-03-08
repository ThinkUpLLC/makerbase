ALTER TABLE actions DROP object_slug, DROP object_name;

ALTER TABLE actions
ADD object2_id INT(11) NULL COMMENT 'ID of second affected object.' ,
ADD object2_type VARCHAR(20) NULL COMMENT 'Type of second affected object.' ,
ADD metadata TEXT NULL COMMENT 'Object metadata.' ;