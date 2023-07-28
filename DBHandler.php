<?php

use AsyncAws\Core\Configuration;

class DBHandler
{
    protected Configuration $config;

    public static function setDB(): Configuration
    {
        return Configuration::create([
            'accessKeyId' => $_ENV['AWS_KEY'],
            'accessKeySecret' => $_ENV['AWS_SECRET'],
            'region' => 'us-east-1',
            'endpoint' => 'http://host.docker.internal:9090'
        ]);
    }
}