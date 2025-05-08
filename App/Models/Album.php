<?php

namespace App\Models;

class Album extends \Core\Model
{
    public static function getAll(): array
    {
        $sql = <<<'SQL'
            SELECT Album.AlbumId, Album.Title AS AlbumTitle, Album.ArtistId, Artist.name AS ArtistName FROM Album 
            INNER JOIN Artist ON Album.ArtistId = Artist.ArtistId
        SQL;

        return self::execute($sql);
    }

    public static function search(string $searchText): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Album WHERE Title LIKE :search
        SQL;

        return self::execute($sql, [
            'search' => "%$searchText%"
        ]);
    }
}