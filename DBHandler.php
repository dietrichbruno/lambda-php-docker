<?php

use AsyncAws\Core\Configuration;
use Dotenv\Dotenv;

class DBHandler
{
    protected Configuration $config;

    public static function setDB(): Configuration
    {
        if ($_ENV['APP_ENV'] == 'local') {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        }

        return Configuration::create([
            'accessKeyId' => $_ENV['AWS_ACCESS_KEY_ID'],
            'accessKeySecret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
            'region' => $_ENV['AWS_REGION'],
            'endpoint' => $_ENV['AWS_DB_ENDPOINT']
        ]);
    }
}