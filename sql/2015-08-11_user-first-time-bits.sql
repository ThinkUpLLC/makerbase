--
-- Add new fields to detect first timers, and temporarily set the default value to null
--
ALTER TABLE users
ADD has_added_maker INT(1) DEFAULT NULL COMMENT 'Whether user has added a maker.' ,
ADD has_added_product INT(1) DEFAULT NULL COMMENT 'Whether user has added a product.' ,
ADD has_added_role INT(1) DEFAULT NULL COMMENT 'Whether user has added a role.' ;

--
-- Run batch script here to populate these fields based on whether or not they are null
--

--
-- Then set default value to 0 for all new users going forward
--
ALTER TABLE users
CHANGE has_added_maker has_added_maker int(1) NOT NULL DEFAULT '0' COMMENT 'Whether user has added a maker.' AFTER is_email_verified,
CHANGE has_added_product has_added_product int(1) NOT NULL DEFAULT '0' COMMENT 'Whether user has added a product.' AFTER has_added_maker,
CHANGE has_added_role has_added_role int(1) NOT NULL DEFAULT '0' COMMENT 'Whether user has added a role.' AFTER has_added_product;

