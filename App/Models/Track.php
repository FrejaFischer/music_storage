<?php

namespace App\Models;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Models\Album;
use App\Models\MediaType;
use App\Models\Genre;

class Track extends \Core\Model
{
    private const MAX_NAME_LENGTH = 200; 
    private const MAX_COMPOSER_LENGTH = 220; 
    private const MAX_PRICE_DIGITS = 10; 
    private const MAX_PRICE_DECIMALS = 2; 

    /**
     * Method for searching for tracks by their name or composer
     * @param string $searchText - The name or composer to search for
     * @param string $searchColumn - The column in the db to search inside
     * @return array - of results
     */
    public static function search(string $searchText, string $searchColumn): array
    {
        // Check if the chosen searchColumn is allowed to search in
        $allowedColumns = ['Name', 'Composer'];

        if (!in_array($searchColumn, $allowedColumns, true)) {
            ResponseHelper::jsonError('System Error - please contact us');
            throw new \Exception("Invalid search column: $searchColumn", 500);
        }

        $sql = <<<SQL
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
            WHERE Track.$searchColumn LIKE :search
        SQL;

        return self::execute($sql, [
            'search' => "%$searchText%"
        ]);
    }

    /**
     * Method for getting specific track by its ID
     * @param int $trackID - the id of the track to get 
     * @return array - of results
     */
    public static function get(int $trackID): array
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
            WHERE Track.TrackId = :trackID
        SQL;

        return self::execute($sql, [
            'trackID' => $trackID
        ]);
    }

    /**
     * Validate function for validating track information. 
     * Accounts for if fields are required, and if not - if they then are present and have a value
     * @param array $data - The track data
     * @param bool $isInsert - If the validation is made on an insert or update of a track
     * @return array of errors, empty array if no errors
     */
    private static function validateTrack(array $data, bool $isInsert = false): array
    {
        $errors = [];

        // Name (if it's an insert or if it's an update and the name is present)
        if ($isInsert || array_key_exists('name', $data)) {
            $name = $data['name'] ?? null;

            if (empty($name) || !is_string($name)) {
                $errors[] = 'Name is required and must be a string.';
            } else if (strlen($name) > self::MAX_NAME_LENGTH) {
                $errors[] = 'Track name is too long - Max ' . self::MAX_NAME_LENGTH . ' characters';
            }
        }

        // AlbumId (if present, and not null (empty string))
        if (array_key_exists('album_id', $data) && $data['album_id'] !== '') {
            // Check if the id is valid
            if (!ValidationHelper::isValidId($data['album_id'])) {
                $errors[] = 'Invalid Album ID - must be numeric';
            } else if (!Album::get($data['album_id'])) {
                // Check if there exist an album with the ID
                $errors[] = 'No album with that ID exists';
            }
        }

        // MediaTypeId (if it's an insert or if it's an update and the media type is present)
        if ($isInsert || array_key_exists('media_type_id', $data)) {
            $mediaTypeId = $data['media_type_id'] ?? null;

            // Check if there is an id
            if (empty($mediaTypeId)) {
                $errors[] = 'Media Type Id is required';
            } else if (!ValidationHelper::isValidId($mediaTypeId)) {
                // Check if the id is valid
                $errors[] = 'Invalid Media Type ID - must be numeric';
            } else if (!MediaType::get($data['media_type_id'])) {
                // Check if there exist a media type with the ID
                $errors[] = 'No media type with that ID exists';
            }
        }

        // GenreId (if present, and not null (empty string))
        if (array_key_exists('genre_id', $data) && $data['genre_id'] !== '') {
            // Check if the id is valid
            if (!ValidationHelper::isValidId($data['genre_id'])) {
                $errors[] = 'Invalid Genre ID - must be numeric';
            } else if (!Genre::get($data['genre_id'])) {
                $errors[] = 'No genre with that ID exists';
            }
        }
            
        // Composer (if present and not null (empty string))
        if (array_key_exists('composer', $data) && $data['composer'] !== '') {
            if (!is_string($data['composer'])) {
                $errors[] = 'Composer must be a string.';
            }

            if (strlen($data['composer']) > self::MAX_COMPOSER_LENGTH) {
                $errors[] = 'Composer is too long - Max ' . self::MAX_COMPOSER_LENGTH . ' characters';
            }
        }

        // Milliseconds (if it's an insert or if it's an update and the media type is present)
        if ($isInsert || array_key_exists('milliseconds', $data)) {
            $milliseconds = $data['milliseconds'] ?? null;
            // Check if milliseconds is an int or numeric string, and have minimum value of 1
            if (!filter_var($milliseconds, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $errors[] = 'Milliseconds is required and must be a positive integer.';
            }
        }

        // Bytes (if present and not null (empty string))
        if (array_key_exists('bytes', $data) && $data['bytes'] !== '') {
            // Check if bytes is an int or numeric string, and have minimum value of 1
            if (!filter_var($data['bytes'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $errors[] = 'Bytes must be a positive integer.';
            }
        }
        
        // UnitPrice (if it's an insert or if it's an update and the media type is present)
        if ($isInsert || array_key_exists('unit_price', $data)) {
            $unitPrice = $data['unit_price'] ?? null;
            // Check if Unit price is a positiv integer (no - in front) and if it matches the DECIMAL format in db
            if (!filter_var($unitPrice, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^\d{1,8}(\.\d{1,2})?$/']])) {
                $errors[] = 'Unit price is required and must be a positive integer, with max ' . self::MAX_PRICE_DIGITS . ' digit - Where ' . self::MAX_PRICE_DECIMALS . ' can be decimals';
            }
        }

        return $errors;
    }

    /**
     * Method for adding a new track
     * @param array $columns - The data to insert
     * @return int|array - Either the id of the new track or array of validation errors
     */
    public static function add(array $columns): int|array
    {
        $validationErrors = [];

        // Validate the new track
        $validationErrors = self::validateTrack($columns, true);

        // If validation fails, return validation errors
        if (!empty($validationErrors)) {
            return $validationErrors;
        }

        // Handle the required columns
        $name = trim($columns['name'] ?? '');
        $mediaTypeId = trim($columns['media_type_id'] ?? '');
        $milliseconds = trim($columns['milliseconds'] ?? '');
        $unitPrice = trim($columns['unit_price'] ?? '');

        $dbColumnNames = ['Name', 'MediaTypeId', 'Milliseconds', 'UnitPrice'];
        $namedParam = [':trackName', ':mediaTypeID', ':milliseconds', ':unitPrice'];
        $params = [
            'trackName' => $name,
            'mediaTypeID' => (int) $mediaTypeId,
            'milliseconds' => (int) $milliseconds,
            'unitPrice' => (float) $unitPrice
        ];

        // Handle the optional columns if they are present
        $optionalFields = [
            'album_id' => ['dbColumnName' => 'AlbumId', 'namedParam' => 'albumID', 'cast' => 'int'],
            'genre_id' => ['dbColumnName' => 'GenreId', 'namedParam' => 'genreID', 'cast' => 'int'],
            'composer' => ['dbColumnName' => 'Composer', 'namedParam' => 'composer', 'cast' => 'string'],
            'bytes' => ['dbColumnName' => 'Bytes', 'namedParam' => 'bytes', 'cast' => 'int'],
        ];

        foreach ($optionalFields as $key => $config) {
            // Check if the optional column is present and not an empty string
            if (isset($columns[$key]) && $columns[$key] !== '') {

                // Casting the value to correct data type
                $value = trim($columns[$key]);
                if ($config['cast'] === 'int') {
                    $value = (int) $value;
                } elseif ($config['cast'] === 'float') {
                    $value = (float) $value;
                }

                // Store the db column name
                $dbColumnNames[] = $config['dbColumnName'];
                // Store the named parameter 
                $namedParam[] = ':' . $config['namedParam'];
                // Store the param and value for the prepared SQL
                $params[$config['namedParam']] = $value;
            }
        }

        // Insert new track in db
        $sql = 'INSERT INTO Track (' . implode(', ', $dbColumnNames) . ') VALUES (' . implode(', ', $namedParam) . ')';

        return self::execute($sql, $params);
    }

    /**
     * Method for updating a track
     * @param array $columns - The data to update the track with
     * @param int $trackID - The id of the the track to update
     * @return bool|array - Either true if rows affected, false if no rows affected (track not found) or array of validation errors
     */
    public static function update(array $columns, int $trackID): bool|array
    {
        $validationErrors = [];

        // Validate the new track
        $validationErrors = self::validateTrack($columns, false);

        // If validation fails, return validation errors
        if (!empty($validationErrors)) {
            return $validationErrors;
        }

        $set = [];
        $params = ['trackID' => $trackID];

        $possibleFields = [
            'name' => ['dbColumnName' => 'Name', 'namedParam' => 'trackName', 'cast' => 'string'],
            'album_id' => ['dbColumnName' => 'AlbumId', 'namedParam' => 'albumID', 'cast' => 'int'],
            'media_type_id' => ['dbColumnName' => 'MediaTypeId', 'namedParam' => 'mediaTypeID', 'cast' => 'int'],
            'genre_id' => ['dbColumnName' => 'GenreId', 'namedParam' => 'genreID', 'cast' => 'int'],
            'composer' => ['dbColumnName' => 'Composer', 'namedParam' => 'composer', 'cast' => 'string'],
            'milliseconds' => ['dbColumnName' => 'Milliseconds', 'namedParam' => 'milliseconds', 'cast' => 'int'],
            'bytes' => ['dbColumnName' => 'Bytes', 'namedParam' => 'bytes', 'cast' => 'int'],
            'unit_price' => ['dbColumnName' => 'UnitPrice', 'namedParam' => 'unitPrice', 'cast' => 'float'],
        ];

        foreach ($possibleFields as $key => $config) {
            // Check if the field is present in the update
            if (isset($columns[$key])) {

                // Trim and cast the value to correct data type
                $value = trim($columns[$key]);
                if ($config['cast'] === 'int') {
                    $value = (int) $value;
                } elseif ($config['cast'] === 'float') {
                    $value = (float) $value;
                }

                // Store the db column name and named parameter (dbColumnName = :namedParam)
                $set[] = $config['dbColumnName'] . ' = :' . $config['namedParam'];
                // Store the param and value for the prepared SQL
                $params[$config['namedParam']] = $value;
            }
        }

        // Check if there is something to update
        if (empty($set)) {
            return ['Found nothing to update'];
        }
    
        $sql = "UPDATE Track SET " . implode(', ', $set) . " WHERE TrackId = :trackID";
        return self::execute($sql, $params);
    }

    /**
     * Method for deleting a track
     * @param int $trackID - The id of the track to delete
     * @return bool - True if succes, false it not
     */
    public static function delete(int $trackID): bool
    {
        $sql = <<<'SQL'
            DELETE FROM Track WHERE TrackId = :trackID
        SQL;

        return self::execute($sql, [
            'trackID' => $trackID
        ]);
    }

    /**
     * Method for checking if track is connected to any playlists
     * @param int $trackID - The track to check with
     * @return bool - True if there exist a connection, false if not
     */
    public static function isConnectedToPlaylist(int $trackID): bool
    {
        $sql = <<<'SQL'
            SELECT * FROM PlaylistTrack WHERE TrackId = :trackID
        SQL;

        $connections = self::execute($sql, [
            'trackID' => $trackID
        ]);

        // Check if there is any connections
        if ($connections) {
            return true;
        } else {
            return false;
        }
    }
}