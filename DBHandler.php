<?php

use AsyncAws\Core\Configuration;
use AsyncAws\DynamoDb\Input\GetItemInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use Dotenv\Dotenv;

class DBHandler
{
    protected Configuration $config;

    public static function getDBConfig(): Configuration
    {
        self::loadEnv();

        return Configuration::create([
            'accessKeyId' => $_ENV['AWS_ACCESS_KEY_ID'],
            'accessKeySecret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
            'region' => $_ENV['AWS_REGION'],
            'endpoint' => $_ENV['AWS_DB_ENDPOINT']
        ]);
    }

    public static function getCepItem($cep): GetItemInput
    {
        return new GetItemInput([
            'TableName' => 'cep-table',
            'ConsistentRead' => true,
            'Key' => [
                'cep' => new AttributeValue(['S' => $cep])
            ],
        ]);
    }

    public static function getCorreiosSessionItem(mixed $type): GetItemInput
    {
        return new GetItemInput([
            'TableName' => 'correios-sessions',
            'ConsistentRead' => true,
            'Key' => [
                'type' => new AttributeValue(['S' => $type])
            ],
        ]);
    }

    public static function createCorreiosSessionEntity(mixed $body): array
    {
        return [
            'TableName' => 'correios-sessions',
            'Item' => [
                'type' => new AttributeValue(['S' => 'production']),
                'token' => new AttributeValue(['S' => $body['token']]),
                'expires_at' => new AttributeValue(['S' => $body['expiraEm']])
            ],
        ];
    }

    private static function loadEnv(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }
}