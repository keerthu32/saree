<?php

declare(strict_types=1);

namespace App;

final class Response
{
    public static function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_PRETTY_PRINT);
    }

    public static function success(mixed $data, int $statusCode = 200): void
    {
        self::json(['success' => true, 'data' => $data], $statusCode);
    }

    public static function error(string $message, int $statusCode = 400, array $details = []): void
    {
        self::json([
            'success' => false,
            'error' => [
                'message' => $message,
                'details' => $details,
            ],
        ], $statusCode);
    }
}
