<?php

$accounts = "
CREATE TABLE accounts (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO accounts (username, email, password, created_at)
VALUES ('admin', 'admin@example.com', '$2y$12$fNORtrXfUSRWppYNsiWIDOC8hLB5zYmmkB/ZJ9FiLIHCD9dUzKDcC', CURRENT_TIMESTAMP);




";
