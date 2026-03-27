
CREATE DATABASE Prompt_Repository;
USE Prompt_Repository;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM ('admin','developpeur') DEFAULT 'developpeur'
);
CREATE TABLE categories(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE prompts(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    user_id INT,
    category_id INT,
    CONSTRAINT FK_category FOREIGN KEY ( category_id )
    REFERENCES categories(id) ON DELETE CASCADE,
    CONSTRAINT FK_user FOREIGN KEY ( user_id )
    REFERENCES users(id) ON DELETE CASCADE 
);

INSERT INTO users (username, email, password,role) 
VALUES ('najwa','najwa@devgenius.com', '$2y$10$JmfMn7g9mz.3epRCzysEu.PXqHQ2bZM2as51LnXpjko7gqqyEiydm' ,'admin');