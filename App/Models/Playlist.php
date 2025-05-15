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
}