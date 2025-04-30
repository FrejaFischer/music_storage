<?php

/**
 * Base model
*/

namespace Core;
use PDO;

use Exception;

 class Model extends \App\Config
 {
    public function getDB(): PDO
    {
        static $db = null;

        if ($db === null) {
            try {
                parent::__construct(); // Call parent constructor to load env variables

                $dsn = 'mysql:host' . $this->host . ';port=' . $this->port .
                ';dbname=' . $this->dbname . 
                ';charset=utf8';
                $db = new PDO($dsn, $this->user, $this->password);

                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                throw new \Exception("Error <strong>{$e->getMessage()}</strong> in model " . get_called_class());
            }
        }

        return $db;
    }
 }
