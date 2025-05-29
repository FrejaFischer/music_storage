<?php

namespace App\Models;

class Genre extends \Core\Model
{
    /**
     * Method for getting all genres
     * @return array of results
     */
     public static function getAll(): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Genre
        SQL;

        return self::execute($sql);
    }

    /**
     * Method for getting genre by id
     * @param int $genreID - The id of the genre
     * @return array of result
     */
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