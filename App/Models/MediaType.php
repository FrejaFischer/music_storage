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
}