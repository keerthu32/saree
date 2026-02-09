CREATE DATABASE IF NOT EXISTS saree_market;
USE saree_market;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(120) UNIQUE NOT NULL,
    email VARCHAR(190) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    photo_filename VARCHAR(255),
    created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(190) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_filename VARCHAR(255),
    created_at DATETIME NOT NULL
);

INSERT INTO users (username, email, password_hash, role, created_at)
SELECT 'admin',
       'admin@saree.com',
       '$2y$10$57sFyyTy5qpxfC0l11RWhOKBzOWA8SmGS2FHTRcX.4vJHr5LtjsYe',
       'admin',
       UTC_TIMESTAMP()
WHERE NOT EXISTS (SELECT 1 FROM users WHERE role = 'admin');
