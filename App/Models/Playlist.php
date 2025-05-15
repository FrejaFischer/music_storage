<?php

namespace App\Models;

class Playlist extends \Core\Model
{
    public static function getAll(): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Playlist
        SQL;

        return self::execute($sql);
    }

    public static function search(string $searchText): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Playlist
            WHERE Name LIKE :search
        SQL;

        return self::execute($sql, [
            'search' => "%$searchText%"
        ]);
    }
}