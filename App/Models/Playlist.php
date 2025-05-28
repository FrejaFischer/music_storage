<?php

namespace App\Models;

use App\Helpers\ValidationHelper;
use App\Models\Track;

class Playlist extends \Core\Model
{
    public const MAX_NAME_LENGTH = 120;

    /**
     * Method for getting all playlists
     * @return array of result
     */
    public static function getAll(): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Playlist
        SQL;

        return self::execute($sql);
    }

    /**
     * Method for searching for playlist by name
     * @param string $searchText - The search
     * @return array of result
     */
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

    /**
     * Method for getting a playlist by id
     * @param int $playlistID - The playlist to find
     * @return array of result
     */
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

    /**
     * Method for getting tracks connected to playlist 
     * @param int $playlistID - The playlist to find tracks from
     * @return array of result
     */
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

    /**
     * Validate function for validating playlist 
     * @param array $data - The playlist data
     * @return array of errors, empty array if no errors
     */
    private static function validate(array $data): array
    {
        $errors = [];

        // Validate name
        if (array_key_exists('name', $data)) {
            $name = $data['name'] ?? null;

            if (empty($name) || !is_string($name)) {
                $errors[] = 'Name is mandatory and must be a string';
            } else if (strlen($name) > self::MAX_NAME_LENGTH) {
                $errors[] = 'Playlist name is too long - Max ' . self::MAX_NAME_LENGTH . ' characters';
            }
        }

        // Validate track ID
        if (array_key_exists('track_id', $data)) {
            $trackID = $data['track_id'] ?? null;

            if (!ValidationHelper::isValidId($trackID)) {
                $errors[] = 'Invalid Track ID - must be numeric';
            } else if (!Track::get($trackID)) {
                $errors[] = 'No track found with that ID';
            }
        }

        return $errors;
    }

    /**
     * Method for adding a new playlist
     * @param array $columns - The data to insert
     * @return int|array - Either the id of the new playlist or array of validation errors
     */
    public static function add(array $columns): int|array
    {
       $validationErrors = [];

        $name = trim($columns['name'] ?? '');
        $validationErrors = self::validate(['name' => $name]);

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

    /**
     * Method for checking if a track is already assigned to a playlist
     * @param int $playlistID - The playlist to check with
     * @param int $trackID - The track to check with
     * @return bool - True if there is an connection, false if not
     */
    private static function checkExistingConnection(int $playlistID, int $trackID): bool
    {
        $sql = <<<'SQL'
            SELECT * FROM PlaylistTrack WHERE PlaylistId = :playlistID AND TrackId = :trackID
        SQL;

        $connections = self::execute($sql, [
            'playlistID' => $playlistID,
            'trackID' => $trackID
        ]);

        // Check if there is any connections
        if ($connections) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method for assigning track to playlist
     * @param array $columns - The track to be assigned
     * @param int $playlistID - The playlist to assign it to
     * @return int|array - Either int if insert if succesfull or array of validation errors
     */
    public static function addTrack(array $columns, int $playlistID): int|array
    {
        $validationErrors = [];

        $trackID = trim($columns['track_id'] ?? null);

        // Validate POST
        $validationErrors = self::validate(['track_id' => $trackID]);
        if (!empty($validationErrors)) {
            return $validationErrors;
        }

        // Check if track is already assigned to playlist
        if (self::checkExistingConnection($playlistID, $trackID)) {
            $validationErrors[] = 'Track is already assigned to the playlist';
        }
        if (!empty($validationErrors)) {
            return $validationErrors;
        }

        // Assign track to playlist
        $sql = <<<'SQL'
            INSERT INTO PlaylistTrack(PlaylistId, TrackId) VALUES (:playlistID,:trackID)
        SQL;
        
        return self::execute($sql, [
            'playlistID' => $playlistID,
            'trackID' => $trackID
        ]);
    }
    
    /**
     * Method for removing a track from a playlist
     * @param int $playlistID - The playlist to remove from
     * @param int $trackID - The track to remove
     * @return bool - True if succes, false it not
     */
    public static function removeTrack(int $playlistID, int $trackID): bool
    {
        $sql = <<<'SQL'
            DELETE FROM PlaylistTrack WHERE PlaylistId = :playlistID AND TrackId = :trackID
        SQL;

        return self::execute($sql, [
            'playlistID' => $playlistID,
            'trackID' => $trackID
        ]);
    }

    /**
     * Method for deleting a playlist
     * @param int $playlistID - The id of the playlist to delete
     * @return bool - True if succes, false it not
     */
    public static function delete(int $playlistID): bool
    {
        $sql = <<<'SQL'
            DELETE FROM Playlist WHERE PlaylistId = :playlistID
        SQL;

        return self::execute($sql, [
            'playlistID' => $playlistID
        ]);
    }
}