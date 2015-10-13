CREATE TABLE cookies (
    cookie varchar(100) not null COMMENT 'Unique cookie key.',
    user_uid varchar(200) not null COMMENT 'UID of the user logged in with this cookie.',
    unique key (cookie),
    index (user_uid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Browser cookies that maintain logged-in user sessions.';