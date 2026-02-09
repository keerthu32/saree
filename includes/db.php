<?php
declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'saree_market';

function db(): mysqli
{
    static $conn = null;
    if ($conn instanceof mysqli) {
        return $conn;
    }

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    if ($conn->connect_error) {
        die('Database connection failed: ' . $conn->connect_error);
    }

    $conn->query('CREATE DATABASE IF NOT EXISTS ' . DB_NAME);
    $conn->select_db(DB_NAME);
    $conn->set_charset('utf8mb4');

    $conn->query(
        'CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(120) UNIQUE NOT NULL,
            email VARCHAR(190) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            role VARCHAR(20) NOT NULL DEFAULT "user",
            photo_filename VARCHAR(255),
            created_at DATETIME NOT NULL
        )'
    );

    $conn->query(
        'CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(190) NOT NULL,
            description TEXT NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            image_filename VARCHAR(255),
            created_at DATETIME NOT NULL
        )'
    );

    $checkStmt = $conn->prepare("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
    $checkStmt->execute();
    $checkStmt->bind_result($adminId);
    $adminExists = $checkStmt->fetch();
    $checkStmt->close();

    if (!$adminExists) {
        $stmt = $conn->prepare(
            'INSERT INTO users (username, email, password_hash, role, created_at)
             VALUES (?, ?, ?, ?, ?)'
        );
        $username = 'admin';
        $email = 'admin@saree.com';
        $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
        $role = 'admin';
        $createdAt = gmdate('Y-m-d H:i:s');
        $stmt->bind_param('sssss', $username, $email, $passwordHash, $role, $createdAt);
        $stmt->execute();
        $stmt->close();
    }

    return $conn;
}
?>
