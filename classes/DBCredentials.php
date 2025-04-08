<?php

require_once 'Utils.php';
abstract Class DBCredentials extends Utils
{
    protected string $host;
    protected string $dbname;
    protected string $user;
    protected string $password;
    protected string $port;

    public function __construct()
    {
        $this->host = Utils::getEnvVariable('DB_HOST');
        $this->dbname = Utils::getEnvVariable("DB_NAME");
        $this->user = Utils::getEnvVariable("DB_USER");
        $this->password = Utils::getEnvVariable("DB_PASS");
        $this->port = Utils::getEnvVariable("DB_PORT");
    }
    
}