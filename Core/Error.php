<?php

/**
 * Error and exception handler
*/

namespace Core;

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

    /**
     * Handle exceptions by logging them to todays error log
     */
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

        Logger::LogError($exceptionInfo);
    }
}