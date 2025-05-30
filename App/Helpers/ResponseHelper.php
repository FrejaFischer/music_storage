<?php

namespace App\Helpers;

class ResponseHelper
{
    /**
     * Send JSON response, with success status
     * @param mixed $data - The data to send
     * @param array $links - The HATEOAS links
     * @param int $statusCode (optional) - The status code, default 200
     */
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

    /**
     * Send JSON response, with error status
     * @param string $message - The main error message
     * @param array $arrayMessage (optional) - An array of errors, if multiple errors should be sent
     */
    public static function jsonError(string $message, ?array $arrayMessage = []): void
    {
        // Sends array of errors if given
        if ($arrayMessage) {
            echo json_encode([
                'status' => 'error',
                'message' => $message,
                'errors' => $arrayMessage
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $message
            ]);
        }
    }
}