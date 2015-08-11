ALTER TABLE users
ADD has_added_maker INT(1) NOT NULL DEFAULT '0' COMMENT 'Whether user has added a maker.' ,
ADD has_added_product INT(1) NOT NULL DEFAULT '0' COMMENT 'Whether user has added a product.' ,
ADD has_added_role INT(1) NOT NULL DEFAULT '0' COMMENT 'Whether user has added a role.' ;

--
-- Assume all existing users have performed these actions
--
UPDATE users SET has_added_maker = 1, has_added_product = 1, has_added_role = 1;