<?php

namespace Core;
use App\Config;

class Env
{
    /**
     * Method for getting variables from env file
     * @param $key - the key to find
     * @return string|null - the variable or null if not found
     */
    static function getEnvVariable($key): string|null
    {
        if (!file_exists(Config::$ROOT_PATH . '/.env')) {
            return null;
        }
        
        $lines = file(Config::$ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($envKey, $envValue) = explode('=', $line, 2);
            if (trim($envKey) === $key) {
                return trim($envValue);
            }
        }
        return null;
    }
}