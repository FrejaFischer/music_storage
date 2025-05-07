<?php

/**
 * Error and exception handler
*/

namespace Core;

use App\Config;

class Error 
{
    /**
     * Errors are turned into exceptions
     */
    public static function errorHandler(
        int $level, string $message, string $file, int $line
    ): void
    {
        if (error_reporting() !== 0) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    public static function exceptionHandler(\Throwable $exception): void
    {
        // HTTP status codes are treated
        $code = $exception->getCode();
        if (!$code) {
            $code = 500;
        }
        http_response_code($code);

        // Error information is formatted
        $exceptionClass = get_class($exception);
        $exceptionInfo = <<<EXCEPTION
        - <br>
        ---------------------------------------------
        <section id="error">
            <p>Uncaught exception: $exceptionClass</p>
            <p>Message: {$exception->getMessage()}</p>
            <p>Stack trace: {$exception->getTraceAsString()}</p>
            <p>Thrown in: {$exception->getFile()} on line {$exception->getLine()}</p>
        </section>
        ---------------------------------------------<br>
        ---------------------------------------------
        EXCEPTION;

        // Log the exception
        if (Config::ENVIRONMENT === 'dev') {
            $log = Config::$ROOT_PATH . '/logs/' . date('Y-m-d') . '.html';
        } else {
            // Path for the logs in production
            $log = Config::$ROOT_PATH . '/api/logs/' . date('Y-m-d') . '.html';
        }
        ini_set('error_log', $log);
        error_log("$exceptionInfo<hr>");
    }
}