<?php

declare(strict_types=1);

return [
    'dsn' => getenv('DB_DSN') ?: 'mysql:host=127.0.0.1;dbname=kurinji_sarees;charset=utf8mb4',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];
