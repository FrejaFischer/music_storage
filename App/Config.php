<?php

namespace App;
use Core\Env;

abstract class Config
{
    const ROOT_PATH = str_replace('\\', '/', __DIR__);
    const SHOW_ERRORS = true;
    
    protected string $host;
    protected string $dbname;
    protected string $user;
    protected string $password;
    protected string $port;
    
    public function __construct()
    {
        $this->host = Env::getEnvVariable('DB_HOST');
        $this->dbname = Env::getEnvVariable("DB_NAME");
        $this->user = Env::getEnvVariable("DB_USER");
        $this->password = Env::getEnvVariable("DB_PASS");
        $this->port = Env::getEnvVariable("DB_PORT");
    }

}
// const BASE_URL = '/exam/music_storage/public/';
// const DB_HOST = 'localhost';
// const DB_NAME = 'Chinook_AutoIncrement';
// const DB_USER = 'root';
// const DB_PORT = '8889';
// const DB_PASSWORD = 'root';