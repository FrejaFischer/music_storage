<?php

namespace App\Models;

class Genre extends \Core\Model
{
     public static function getAll(): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Genre
        SQL;

        return self::execute($sql);
    }

    public static function get(int $genreID): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Genre
            WHERE GenreId = :genreID
        SQL;

        return self::execute($sql, [
            'genreID' => $genreID
        ]);
    }
}