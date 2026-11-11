-- Create users table
CREATE TABLE users (
    id            BIGSERIAL     PRIMARY KEY,
    firstname     VARCHAR(100)  NOT NULL,
    lastname      VARCHAR(100)  NOT NULL,
    ident_number  VARCHAR(20)   NOT NULL UNIQUE,
    mobile_phone  VARCHAR(20),
    email         VARCHAR(150)  NOT NULL UNIQUE,
    password      VARCHAR(255)  NOT NULL,
    url_photo     TEXT,
    birth_date    DATE,
    status        BOOLEAN       NOT NULL DEFAULT TRUE,
    created_at    TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
    deleted_at    TIMESTAMPTZ
);

-- Selected all users from users table
SELECT * FROM users;
-- Insert a new users into users table
INSERT INTO users (firstname, lastname, ident_number, email, password)
mainstore-# VALUES ('Cristian', 'Saavedra', '1085260427', 'cristian@mail.com', '123456');