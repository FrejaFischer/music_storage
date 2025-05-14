<?php

namespace App\Models;

use App\Helpers\ResponseHelper;

class Track extends \Core\Model
{
    /**
     * Method for searching for tracks by their name or composer
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
}