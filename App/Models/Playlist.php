<?php

namespace App\Models;

class Playlist extends \Core\Model
{
    public const MAX_NAME_LENGTH = 120;

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

    public static function get(int $playlistID): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Playlist 
            WHERE PlaylistId = :playlistID
        SQL;

        return self::execute($sql, [
            'playlistID' => $playlistID
        ]);
    }

    public static function getTracks(int $playlistID): array
    {
        $sql = <<<'SQL'
            SELECT Track.TrackId, Track.Name
            FROM Track 
            INNER JOIN PlaylistTrack ON PlaylistTrack.TrackId = Track.TrackId
            WHERE PlaylistTrack.PlaylistId = :playlistID
        SQL;

        return self::execute($sql, [
            'playlistID' => $playlistID
        ]);
    }

    private static function validateName(string $name): array
    {
        $errors = [];
        if (empty($name) || !is_string($name)) {
            $errors[] = 'Name is mandatory and must be a string';
        } else if (strlen($name) > self::MAX_NAME_LENGTH) {
            $errors[] = 'Playlist name is too long - Max ' . self::MAX_NAME_LENGTH . ' characters';
        }
        return $errors;
    }

    public static function add(array $columns): int|array
    {
       $validationErrors = [];

        $name = trim($columns['name'] ?? '');
        $validationErrors = self::validateName($name);

        if (!empty($validationErrors)) {
            return $validationErrors;
        }

        $sql = <<<'SQL'
            INSERT INTO Playlist(Name) VALUES (:playlistName)
        SQL;

        return self::execute($sql, [
            'playlistName' => $name
        ]);
    }
}