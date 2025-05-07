<?php

namespace App;

use Core\Env;
abstract class Config
{
    const ENVIRONMENT = 'dev'; // Which environment is active (development or production)
    
    public static string $ROOT_PATH; // The root path of the application. It is a file system path
    public static string $BASE_URL; // The base URL of the application. It is a web URL
    public static string $LOG_PATH; // The log path

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
        // Set ROOT_PATH
        self::$ROOT_PATH = dirname(__DIR__);

        // Set BASE_URL depending on environment
        $documentRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])); // Web server's document root
        
        $baseUrl = str_replace($documentRoot, '', self::$ROOT_PATH); // The base URL is the current path minus the document root
        
        if (self::ENVIRONMENT==='dev') {
            // As it is an absolute path, it must start with a slash.
            // If it already starts with a slash, ltrim removes it before it gets added again
            self::$BASE_URL = '/' . ltrim($baseUrl, '/') . '/public/'; // equal to: /exam/music_storage/public (Development)
        } else {
            self::$BASE_URL = '/' . ltrim($baseUrl, '/') . 'api/'; // equal to: /api (Production)
        }

        // Set LOG_PATH depending on environment
        if (self::ENVIRONMENT === 'dev') {
            self::$LOG_PATH = Config::$ROOT_PATH . "/logs/";
        } else {
            self::$LOG_PATH = Config::$ROOT_PATH . "/api/logs/";
        }

        // Set database variables
        self::$DB_HOST = Env::getEnvVariable('DB_HOST');
        self::$DB_NAME = Env::getEnvVariable('DB_NAME');
        self::$DB_USER = Env::getEnvVariable('DB_USER');
        self::$DB_PASSWORD = Env::getEnvVariable('DB_PASS');
        self::$DB_PORT = Env::getEnvVariable('DB_PORT');

        // Load API keys
        $keys = Env::getEnvVariable('API_KEYS');
        self::$API_KEYS = array_map('trim', explode(',', $keys)); // Convert to array
    }
    
}