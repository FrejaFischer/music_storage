<?php

namespace Core;
use App\Config;

class Env
{
    static function getEnvVariable($key): string|null
    {
        if (!file_exists(Config::ROOT_PATH . '/.env')) {
            return null;
        }
        
        $lines = file(Config::ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($envKey, $envValue) = explode('=', $line, 2);
            if (trim($envKey) === $key) {
                return trim($envValue);
            }
        }
        return null;
    }
}