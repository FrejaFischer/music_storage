<?php

/**
 * Base model
*/

namespace Core;

use PDO;
use PDOException;
use App\Config;
use App\Helpers\ResponseHelper;

abstract class Model
 {
    // Database error messages (Production mode)
    private const DB_CONN_ERROR = 'Database connection unsuccessful';
    private const DB_SQL_ERROR = 'Database query unsuccessful';

    /**
     * Connects to database, creates PDO
     * @return PDO
     */
    protected static function getDB(): PDO
    {
        static $db = null;

        if ($db === null) {
            try {
                $dsn = 'mysql:host=' . Config::$DB_HOST .
                    ';port=' . Config::$DB_PORT .
                    ';dbname=' . Config::$DB_NAME .
                    ';charset=utf8';

                $db = new PDO($dsn, Config::$DB_USER, Config::$DB_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                ResponseHelper::jsonError(self::DB_CONN_ERROR);

                throw new \Exception("Error <strong>{$e->getMessage()}</strong> in model " . get_called_class(), 500);
            }
        }

        return $db;
    }
    
     /**
     * For SELECTs, it returns the query results as an associative array.
     * For INSERTs, it returns the new PK value.
     * For DELETEs and UPDATEs, it returns whether some rows has been affected.
     */
    protected static function execute(string $sql, array $params = []): array|int|bool
    {
        try {
            $db = static::getDB();
    
            if (empty($params)) {
                $stmt = $db->query($sql);
            } else {
                $stmt = $db->prepare($sql);
                foreach ($params as $key => $value) {
                    $stmt->bindValue(":{$key}", $value);
                }
                $stmt->execute();
            }
    
            switch (substr(ltrim($sql), 0, 6)) {
                case 'SELECT':
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                case 'INSERT':
                    return $db->lastInsertId();
                case 'DELETE':
                case 'UPDATE':
                    return $stmt->rowCount() > 0;
                default:
                    return 0;
            }
        } catch (PDOException $e) {
            ResponseHelper::jsonError(self::DB_SQL_ERROR);

            throw new \Exception("Error <strong>{$e->getMessage()}</strong> in model " . get_called_class(), 500);
        }
    }
 }
