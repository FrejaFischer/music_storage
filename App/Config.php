<?php

namespace App;

use Core\Env;
abstract class Config
{
    const SHOW_ERRORS = true; // true = Development mode, false = Production mode
    
    public static string $ROOT_PATH;

    public static string $DB_HOST;
    public static string $DB_NAME;
    public static string $DB_USER;
    public static string $DB_PASSWORD;
    public static string $DB_PORT;

    public static array $API_KEYS;
    
    // const BASE_URL = '/exam/music_storage/public/';

    /**
     * Initialise credentials to database and sets the Root path
     */
    public static function init(): void
    {
        self::$ROOT_PATH = dirname(__DIR__);

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