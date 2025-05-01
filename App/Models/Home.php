<?php

namespace App\Models;
use PDOException;

class Home extends \Core\Model
{
    public static function getAll(): array
    {
        try {
            $sql = <<<'SQL'
                SELECT * FROM Album ORDER BY Title
            SQL;

            return self::execute($sql);
        } catch (PDOException $e) {
            if (\App\Config::SHOW_ERRORS) {
                throw new \Exception("Error <strong>{$e->getMessage()}</strong> in model " . get_called_class(), 500);
            } else {
                throw new \Exception(self::DB_SQL_ERROR, 500);
            }
        }
    }
}