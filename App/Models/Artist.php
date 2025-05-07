<?php

namespace App\Models;

class Artist extends \Core\Model
{
    public static function getAll(): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Artist ORDER BY Name
        SQL;

        return self::execute($sql);
    }

    public static function search(string $searchText): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Artist WHERE Name LIKE :search
        SQL;

        return self::execute($sql, [
            'search' => "%$searchText%"
        ]);
    }

    public static function get(int $artistID): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Artist WHERE ArtistId = :artistID
        SQL;

        return self::execute($sql, [
            'artistID' => $artistID
        ]);
    }

    public static function getAlbums(int $artistID): array
    {
        $sql = <<<'SQL'
        SELECT * FROM Album WHERE ArtistId = :artistID
        SQL;

        return self::execute($sql, [
            'artistID' => $artistID
        ]);
    }

    public static function delete(int $artistID): bool
    {
        $sql = <<<'SQL'
        DELETE FROM Artist WHERE ArtistId = :artistID
        SQL;

        return self::execute($sql, [
            'artistID' => $artistID
        ]);
    }
}