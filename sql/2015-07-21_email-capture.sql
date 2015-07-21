ALTER TABLE users ADD email VARCHAR(200) NULL DEFAULT NULL COMMENT 'User email address.'
AFTER is_frozen, ADD email_verification_code INT(10) NULL DEFAULT NULL
COMMENT 'Email verification code.' AFTER email,
ADD is_email_verified INT(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not email is verified.' AFTER email_verification_code;