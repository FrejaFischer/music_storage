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
            SELECT Album.AlbumId, 
            Album.Title AS AlbumTitle, 
            Album.ArtistId, 
            Artist.Name AS ArtistName 
            FROM Album 
            INNER JOIN Artist ON Album.ArtistId = Artist.ArtistId
            WHERE Title LIKE :search
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

    private static function validateTitle(string $title): array
    {
        $errors = [];
        if (empty($title) || !is_string($title)) {
            $errors[] = 'Title is mandatory and must be a string';
        }
        if (strlen($title) > self::MAX_TITLE_LENGTH) {
            $errors[] = 'Album title is too long - Max ' . self::MAX_TITLE_LENGTH . ' characters';
        }
        return $errors;
    }

    private static function validateArtistId(int $artistId): array
    {
        $errors = [];
        if (!Artist::get($artistId)) {
            $errors[] = 'No artist with that ID exists';
        }
        return $errors;
    }

    public static function add(array $columns): int|array
    {
        $validationErrors = [];

        $title = trim($columns['title'] ?? '');
        $titleErrors = self::validateTitle($title);
        if ($titleErrors) {
            $validationErrors = array_merge($validationErrors, $titleErrors);
        }

        $artistId = (int) trim($columns['artist_id'] ?? '');
        $artistErrors = self::validateArtistId($artistId);
        if ($artistErrors) {
            $validationErrors = array_merge($validationErrors, $artistErrors);
        }

        if (!empty($validationErrors)) {
            return $validationErrors;
        }

        $sql = <<<'SQL'
        INSERT INTO Album(Title, ArtistId) VALUES (:albumTitle, :artistID)
        SQL;

        return self::execute($sql, [
            'albumTitle' => $title,
            'artistID' => $artistId
        ]);
    }

    public static function update(array $columns, int $albumID): int|array
    {
        $set = [];
        $params = ['albumID' => $albumID];
        $validationErrors = [];
    
        // Title
        if (isset($columns['title'])) {
            $title = trim($columns['title']);
            $titleErrors = self::validateTitle($title);
            if ($titleErrors) {
                $validationErrors = array_merge($validationErrors, $titleErrors);
            } else {
                $set[] = "Title = :albumTitle";
                $params['albumTitle'] = $title;
            }
        }
    
        // Artist ID
        if (isset($columns['artist_id'])) {
            $artistId = (int) trim($columns['artist_id']);
            $artistErrors = self::validateArtistId($artistId);
            if ($artistErrors) {
                $validationErrors = array_merge($validationErrors, $artistErrors);
            } else {
                $set[] = "ArtistId = :artistID";
                $params['artistID'] = $artistId;
            }
        }
    
        if (!empty($validationErrors)) {
            return $validationErrors;
        }
    
        if (empty($set)) {
            return ['Found nothing to update'];
        }
    
        $sql = "UPDATE Album SET " . implode(', ', $set) . " WHERE AlbumId = :albumID";
        return self::execute($sql, $params);
    }
}