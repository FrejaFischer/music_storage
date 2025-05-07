<?php

namespace Core;

use App\Config;

class Logger 
{
    /**
     * Gets the path of the log directory (depending on environment)
     * @param string $type - Type of log (error or request)
     * @return string|false - the path or false if error
     */
    private static function getLogPath(string $type): string|false
    {
        $baseDir = Config::ENVIRONMENT === 'dev' 
            ? Config::$ROOT_PATH . "/logs/$type/" 
            : Config::$ROOT_PATH . "/api/logs/$type/";
        
        return $baseDir . date('Y-m-d') . '.html';
    }

    public static function LogError(string $info): void
    {
        ini_set('error_log', self::getLogPath('errors'));
        error_log("$info<hr>");
    
    }

    public static function LogRequest(string $info): void
    {
        file_put_contents(self::getLogPath('requests'), "$info<hr>" . PHP_EOL, FILE_APPEND);
    }

}