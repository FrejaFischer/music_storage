<?php

namespace App\Models;

class Track extends \Core\Model
{
    public static function search(string $searchText): array
    {
        $sql = <<<'SQL'
            SELECT Track.TrackId, 
            Track.Name, 
            Track.AlbumId, 
            Track.MediaTypeId, 
            MediaType.Name AS MediaTypeName, 
            Track.GenreId, 
            Genre.Name AS GenreName, 
            Track.Composer, 
            Track.Milliseconds, 
            Track.Bytes, 
            Track.UnitPrice 
            FROM Track 
            INNER JOIN MediaType ON MediaType.MediaTypeId = Track.MediaTypeId
            INNER JOIN Genre ON Genre.GenreId = Track.GenreId
            WHERE Track.Name LIKE :search
        SQL;

        return self::execute($sql, [
            'search' => "%$searchText%"
        ]);
    }
}