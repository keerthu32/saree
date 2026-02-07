<?php
return [
    'driver' => getenv('DB_DRIVER') ?: 'mysql',
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'name' => getenv('DB_NAME') ?: 'handloom_ecommerce',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
    'sqlite_path' => getenv('DB_SQLITE_PATH') ?: __DIR__ . '/database.sqlite',
];
