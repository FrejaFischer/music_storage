<?php

namespace App;

use Core\Env;
abstract class Config
{
    const SHOW_ERRORS = true; // true = Development mode, false = Production mode
    
    public static string $ROOT_PATH; // The root path of the application. It is a file system path
    public static string $BASE_URL; // The base URL of the application. It is a web URL

    public static string $DB_HOST;
    public static string $DB_NAME;
    public static string $DB_USER;
    public static string $DB_PASSWORD;
    public static string $DB_PORT;

    public static array $API_KEYS;

    /**
     * Initialise credentials to database and sets the Root path and Base URL
     */
    public static function init(): void
    {
        self::$ROOT_PATH = dirname(__DIR__);

        
        // Web server's document root ("C:\xampp\htdocs", "var/www/html" or similar)
        $documentRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
        
        // The base URL is the present script path minus the document root
        $baseUrl = str_replace($documentRoot, '', self::$ROOT_PATH);
        
        // Set BASE_URL depending on if we are in development or production
        if (self::SHOW_ERRORS) {
            // As it is an absolute path, it must start with a slash.
            // If it already starts with a slash, ltrim removes it before it gets added again
            self::$BASE_URL = '/' . ltrim($baseUrl, '/') . '/public/'; // equal to: /exam/music_storage/public (Development)
        } else {
            self::$BASE_URL = '/' . ltrim($baseUrl, '/') . 'api/'; // equal to: /api (Production)
        }

        self::$DB_HOST = Env::getEnvVariable('DB_HOST');
        self::$DB_NAME = Env::getEnvVariable('DB_NAME');
        self::$DB_USER = Env::getEnvVariable('DB_USER');
        self::$DB_PASSWORD = Env::getEnvVariable('DB_PASS');
        self::$DB_PORT = Env::getEnvVariable('DB_PORT');

        // Load API keys
        $keys = Env::getEnvVariable('API_KEYS'); // comma-separated string
        self::$API_KEYS = array_map('trim', explode(',', $keys)); // Convert to array
    }
    
}