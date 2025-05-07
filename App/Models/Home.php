<?php

namespace App\Models;

class Home extends \Core\Model
{
    public static function getAll(): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Album ORDER BY Title
        SQL;

        return self::execute($sql);
    }
}