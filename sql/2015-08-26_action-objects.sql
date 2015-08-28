CREATE TABLE action_objects (
    id INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.' ,
    action_id INT(11) NOT NULL COMMENT 'Action ID.' ,
    object_id INT(11) NOT NULL COMMENT 'Product, maker, user ID.' ,
    object_type VARCHAR(20) NOT NULL COMMENT 'Type of object (Maker, Product, User).' ,
    PRIMARY KEY (id),
    KEY object_id (object_id, object_type)
) ENGINE=InnoDB COMMENT='Objects involved in each action.';

INSERT INTO action_objects (action_id, object_id, object_type)
(SELECT id, object_id, object_type FROM actions);

INSERT INTO action_objects (action_id, object_id, object_type)
(SELECT id, object2_id, object2_type FROM actions WHERE object2_id IS NOT NULL AND object2_type IS NOT NULL);

INSERT INTO action_objects (action_id, object_id, object_type)
(SELECT id, user_id, 'User' FROM actions);
