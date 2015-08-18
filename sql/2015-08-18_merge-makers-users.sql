--
-- Migrate autofills columns to makers
--
ALTER TABLE makers
ADD  autofill_network_id varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Network ID of the maker from autofill.',
ADD  autofill_network varchar(25) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Source network of the autofill.',
ADD  autofill_network_username varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Network username of the maker from autofill.';

--
-- Migrate autofills columns to products
--
ALTER TABLE products
ADD autofill_network_id varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Network ID of the product from autofill.',
ADD autofill_network varchar(25) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Source network of the autofill.',
ADD autofill_network_username varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Network username of the product from autofill.';

--
-- Migrate autofills data to makers
--
UPDATE makers m INNER JOIN autofills a ON a.maker_id = m.id
SET m.autofill_network_id = a.network_id, m.autofill_network = a.network, m.autofill_network_username = a.network_username
WHERE a.maker_id IS NOT NULL;

--
-- Migrate autofills data to products
--
UPDATE products p INNER JOIN autofills a ON a.product_id = p.id
SET p.autofill_network_id = a.network_id, p.autofill_network = a.network, p.autofill_network_username = a.network_username
WHERE a.product_id IS NOT NULL;

--
-- Migrate autofills data to users
--
UPDATE users u INNER JOIN autofills a ON a.network_id = u.twitter_user_id SET u.maker_id = a.maker_id WHERE a.maker_id IS NOT NULL;

DROP TABLE autofills;