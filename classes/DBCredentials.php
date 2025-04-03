<?php
   
function getEnvVariable($key) {
    if (!file_exists(ROOT_PATH . '/.env')) {
        return null;
    }
    
    $lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($envKey, $envValue) = explode('=', $line, 2);
        if (trim($envKey) === $key) {
            return trim($envValue);
        }
    }
    return null;
}
abstract Class DBCredentials
{
    protected string $host;
    protected string $dbname;
    protected string $user;
    protected string $password;
    protected string $port;

    public function __construct()
    {
        $this->host = getEnvVariable('DB_HOST');
        $this->dbname = getEnvVariable("DB_NAME");
        $this->user = getEnvVariable("DB_USER");
        $this->password = getEnvVariable("DB_PASS");
        $this->port = getEnvVariable("DB_PORT");
    }
    
}