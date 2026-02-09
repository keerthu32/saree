<?php

declare(strict_types=1);

namespace App;

use PDO;

final class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $config = require __DIR__ . '/../config/database.php';

        self::$pdo = new PDO(
            $config['dsn'],
            $config['username'],
            $config['password'],
            $config['options']
        );

        return self::$pdo;
    }
}
