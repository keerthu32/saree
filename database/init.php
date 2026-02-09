<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Database.php';

use App\Database;

$pdo = Database::connection();
$schema = file_get_contents(__DIR__ . '/schema.mysql.sql');
$pdo->exec($schema);

echo "Database initialized successfully.\n";
