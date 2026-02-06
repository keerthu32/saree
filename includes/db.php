<?php
$driver = getenv('DB_DRIVER') ?: 'mysql';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    if ($driver === 'sqlite') {
        $path = getenv('DB_SQLITE_PATH') ?: __DIR__ . '/../database.sqlite';
        $dsn = "sqlite:{$path}";
        $pdo = new PDO($dsn, null, null, $options);
    } else {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $dbname = getenv('DB_NAME') ?: 'kurinji_sarees';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, $options);
    }
} catch (PDOException $e) {
    die('Database connection failed.');
}
