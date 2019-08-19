CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories
(
    id INT(12) NOT NULL AUTO_INCREMENT PRIMARY_KEY,
    name CHAR(64) NOT NULL UNIQUE,
    symbol_code CHAR(64) NOT NULL UNIQUE
);

CREATE TABLE lots
(
    id INT(12)  AUTO_INCREMENT NOT NULL PRIMARY_KEY,
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name CHAR(64) NOT NULL,
    description CHAR(255) NOT NULL,
    image CHAR(255) NOT NULL,
    start_price INT(12)  NOT NULL,
    end_date DATE NOT NULL,
    bid_step INT(12)  NOT NULL,
    author_id INT(12)  NOT NULL,
    winner_id INT(12)  NOT NULL,
    category_id INT(12)  NOT NULL
);

CREATE TABLE bids
(
    id INT(12)  AUTO_INCREMENT NOT NULL PRIMARY_KEY,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sum INT(12)  NOT NULL,
    author_id INT(12)  NOT NULL,
    lot_id INT(12)  NOT NULL
);

CREATE TABLE users
(
    id INT(12)  AUTO_INCREMENT NOT NULL PRIMARY_KEY,
    register_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email CHAR(64) NOT NULL UNIQUE,
    name CHAR(64) NOT NULL,
    password CHAR(64) NOT NULL,
    avatar CHAR(64),
    contact_info CHAR(255) NOT NULL,
    created_lot INT(12) ,
    created_bid INT(12)
);

CREATE UNIQUE INDEX category_name ON categories(name);
CREATE INDEX lot_name ON lots(name);
CREATE INDEX bid_sum ON bids(sum);
CREATE INDEX user_name ON users(name);
