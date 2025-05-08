<?php

namespace App\Models;

use App\Models\Artist;

class Album extends \Core\Model
{
    public const MAX_TITLE_LENGTH = 160;

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

    private static function validate(array $columns): array
    {
        $validationErrors = [];

        $title = trim($columns['title'] ?? '');
        $artistId = trim($columns['artist_id'] ?? '');

        // Validate title
        if (empty($title)) {
            $validationErrors[] = 'Title is mandatory';
        }
        if (strlen($title) > self::MAX_TITLE_LENGTH) {
            $validationErrors[] = 'Albums title is too long - Max ' . self::MAX_TITLE_LENGTH . ' characters';
        }

        // Check if artist exist
        $artistExist = Artist::get($artistId);
        if (!$artistExist) {
            $validationErrors[] = 'No artist with that id exists';
        }


        return $validationErrors;
    }

    public static function add(array $columns): int|array
    {
        $validationErrors = self::validate($columns);

        if (!empty($validationErrors)) {
            return $validationErrors;
        }

        $title = trim($columns['title'] ?? '');
        $artistId = trim($columns['artist_id'] ?? '');

        $sql = <<<'SQL'
        INSERT INTO Album(Title, ArtistId) VALUES (:albumTitle, :artistID)
        SQL;

        return self::execute($sql, [
            'albumTitle' => $title,
            'artistID' => $artistId
        ]);
    }
}