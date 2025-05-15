<?php

namespace App\Models;

class MediaType extends \Core\Model
{
    public static function getAll(): array
    {
        $sql = <<<'SQL'
            SELECT * FROM MediaType
        SQL;

        return self::execute($sql);
    }

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