<?php
$config = require __DIR__ . '/../config.php';
$driver = $config['driver'];
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    if ($driver === 'sqlite') {
        $path = $config['sqlite_path'];
        $dsn = "sqlite:{$path}";
        $pdo = new PDO($dsn, null, null, $options);
    } else {
        $host = $config['host'];
        $dbname = $config['name'];
        $user = $config['user'];
        $pass = $config['pass'];
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, $options);
    }
} catch (PDOException $e) {
    die('Database connection failed.');
}
