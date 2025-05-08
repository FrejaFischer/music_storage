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

    public static function get(int $albumID): array
    {
        $sql = <<<'SQL'
            SELECT Album.AlbumId, Album.Title AS AlbumTitle, Album.ArtistId, Artist.name AS ArtistName FROM Album 
            INNER JOIN Artist ON Album.ArtistId = Artist.ArtistId 
            WHERE AlbumId = :albumID
        SQL;

        return self::execute($sql, [
            'albumID' => $albumID
        ]);
    }

    public static function getTracks(int $albumID): array
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
        Track.UnitPrice FROM Track 
        INNER JOIN MediaType ON MediaType.MediaTypeId = Track.MediaTypeId
        INNER JOIN Genre ON Genre.GenreId = Track.GenreId 
        WHERE AlbumId = :albumID
        SQL;

        return self::execute($sql, [
            'albumID' => $albumID
        ]);
    }

    public static function delete(int $albumID): bool
    {
        $sql = <<<'SQL'
        DELETE FROM Album WHERE AlbumId = :albumID
        SQL;

        return self::execute($sql, [
            'albumID' => $albumID
        ]);
    }
}