<?php

namespace App\Models;

class MediaType extends \Core\Model
{
    /**
     * Method for getting all media types
     * @return array of results
     */
    public static function getAll(): array
    {
        $sql = <<<'SQL'
            SELECT * FROM MediaType
        SQL;

        return self::execute($sql);
    }

    /**
     * Method for getting media type by id
     * @param int $mediaTypeID - The id of the media type
     * @return array of result
     */
    public static function get(int $mediaTypeID): array
    {
        $sql = <<<'SQL'
            SELECT * FROM MediaType
            WHERE MediaTypeId = :mediaTypeID
        SQL;

        return self::execute($sql, [
            'mediaTypeID' => $mediaTypeID
        ]);
    }
}