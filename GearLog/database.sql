CREATE DATABASE gearlog;
USE gearlog;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name varchar(100) NOT NULL
);

CREATE TABLE assets (
    serial_number varchar(100) PRIMARY KEY ,
    device_name  VARCHAR(150) NOT NULL,
    status ENUM ('Deployed','Under Repair'),
    price DECIMAL(10,2),
    category_id INT,
    CONSTRAINT FK_category FOREIGN KEY ( category_id )
    REFERENCES categories(id)
);

INSERT INTO categories (name) VALUES
('Laptop'),
('Monitor'),
('Server');

