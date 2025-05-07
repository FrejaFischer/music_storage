<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function jsonResponse(mixed $data, array $links, ?int $statusCode = 200): void
    {
        http_response_code($statusCode);

        echo json_encode([
            'status' => 'success',
            'data' => $data,
            '_links' => $links
        ]);
        exit;
    }

    public static function jsonError(string $message): void
    {
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
    }
}