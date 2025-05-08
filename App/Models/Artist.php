<?php

namespace App\Models;

class Artist extends \Core\Model
{
    public const MAX_NAME_LENGTH = 120;

    public static function getAll(): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Artist ORDER BY Name
        SQL;

        return self::execute($sql);
    }

    public static function search(string $searchText): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Artist WHERE Name LIKE :search
        SQL;

        return self::execute($sql, [
            'search' => "%$searchText%"
        ]);
    }

    public static function get(int $artistID): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Artist WHERE ArtistId = :artistID
        SQL;

        return self::execute($sql, [
            'artistID' => $artistID
        ]);
    }

    public static function getAlbums(int $artistID): array
    {
        $sql = <<<'SQL'
        SELECT * FROM Album WHERE ArtistId = :artistID
        SQL;

        return self::execute($sql, [
            'artistID' => $artistID
        ]);
    }

    public static function delete(int $artistID): bool
    {
        $sql = <<<'SQL'
        DELETE FROM Artist WHERE ArtistId = :artistID
        SQL;

        return self::execute($sql, [
            'artistID' => $artistID
        ]);
    }

    private static function validate(array $columns): array
    {
        $validationErrors = [];

        $name = trim($columns['name'] ?? '');

        if (empty($name)) {
            $validationErrors[] = 'Name is mandatory';
        }
        if (strlen($name) > self::MAX_NAME_LENGTH) {
            $validationErrors[] = 'Artists name is too long - Max ' . self::MAX_NAME_LENGTH . ' characters';
        }
        return $validationErrors;
    }

    public static function add(array $columns): int|array
    {
        $validationErrors = self::validate($columns);

        if (!empty($validationErrors)) {
            return $validationErrors;
        }

        $name = trim($columns['name'] ?? '');

        $sql = <<<'SQL'
        INSERT INTO Artist(Name) VALUES (:artistName)
        SQL;

        return self::execute($sql, [
            'artistName' => $name
        ]);
    }
}