SET GLOBAL innodb_status_output=ON;
SET GLOBAL innodb_status_output_locks=ON;

CREATE TABLE users
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(50)  NOT NULL,
    age        INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users(name, age) VALUES ("Alice", 25);
INSERT INTO users(name, age) VALUES ("Bob", 31);
INSERT INTO users(name, age) VALUES ("Mike", 23);
