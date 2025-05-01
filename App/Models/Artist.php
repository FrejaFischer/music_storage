<?php

namespace App\Models;

use PDOException;

class Artist extends \Core\Model
{
    public static function getAll(): array
    {
        try {
            $sql = <<<'SQL'
                SELECT * FROM Artist ORDER BY Name
            SQL;

            return self::execute($sql);
        } catch (PDOException $e) {
            if (\App\Config::SHOW_ERRORS) {
                throw new \Exception("Error <strong>{$e->getMessage()}</strong> in model " . get_called_class());
            } else {
                throw new \Exception(self::DB_SQL_ERROR);
            }
        }
    }

    public static function search(string $searchText): array
    {
        try {
            $sql = <<<'SQL'
                SELECT * FROM Artist WHERE Name LIKE :search
            SQL;

            return self::execute($sql, [
                'search' => "%$searchText%"
            ]);
        } catch (PDOException $e) {
            if (\App\Config::SHOW_ERRORS) {
                throw new \Exception("Error <strong>{$e->getMessage()}</strong> in model " . get_called_class());
            } else {
                throw new \Exception(self::DB_SQL_ERROR);
            }
        }
    }

    public static function get(int $artistID): array
    {
        try {
            $sql = <<<'SQL'
                SELECT * FROM Artist WHERE ArtistId = :artistID
            SQL;

            return self::execute($sql, [
                'artistID' => $artistID
            ]);
        } catch (PDOException $e) {
            if (\App\Config::SHOW_ERRORS) {
                throw new \Exception("Error <strong>{$e->getMessage()}</strong> in model " . get_called_class());
            } else {
                throw new \Exception(self::DB_SQL_ERROR);
            }
        }
    }
}