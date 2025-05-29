<?php

namespace App\Models;

class Artist extends \Core\Model
{
    private const MAX_NAME_LENGTH = 120;

    /**
     * Method for getting all artists
     * @return array of results
     */
    public static function getAll(): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Artist ORDER BY Name
        SQL;

        return self::execute($sql);
    }

    /**
     * Method for searching for artist by name
     * @param string $searchText - The name to search for
     * @return array of results
     */
    public static function search(string $searchText): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Artist WHERE Name LIKE :search
        SQL;

        return self::execute($sql, [
            'search' => "%$searchText%"
        ]);
    }

    /**
     * Method for getting artist by id
     * @param int $artistID - The id of the artist
     * @return array of result
     */
    public static function get(int $artistID): array
    {
        $sql = <<<'SQL'
            SELECT * FROM Artist WHERE ArtistId = :artistID
        SQL;

        return self::execute($sql, [
            'artistID' => $artistID
        ]);
    }

    /**
     * Method for getting an artists albums
     * @param int $artistID - the artist to get albums from
     * @return array of albums
     */
    public static function getAlbums(int $artistID): array
    {
        $sql = <<<'SQL'
        SELECT * FROM Album WHERE ArtistId = :artistID
        SQL;

        return self::execute($sql, [
            'artistID' => $artistID
        ]);
    }
    
    /**
     * Method for deleting an artist
     * @param int $artistID - the artist to delete
     * @return bool - true if row affected, false if not
     */
    public static function delete(int $artistID): bool
    {
        $sql = <<<'SQL'
        DELETE FROM Artist WHERE ArtistId = :artistID
        SQL;

        return self::execute($sql, [
            'artistID' => $artistID
        ]);
    }

    /**
     * Method for validating an artist
     * @param array $columns - to validate
     * @return array of errors, empty if no errors
     */
    private static function validate(array $columns): array
    {
        $validationErrors = [];

        $name = trim($columns['name'] ?? '');

        if (empty($name) || !is_string($name)) {
            $validationErrors[] = 'Name is mandatory and must be a string';
        }
        if (strlen($name) > self::MAX_NAME_LENGTH) {
            $validationErrors[] = 'Artists name is too long - Max ' . self::MAX_NAME_LENGTH . ' characters';
        }
        return $validationErrors;
    }

    /**
     * Method for inserting new artist
     * @param array $columns - the data to insert
     * @return int|array - the id of the new artist or array of validation errors
     */
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