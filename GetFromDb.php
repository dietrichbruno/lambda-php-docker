<?php

require __DIR__ . '/vendor/autoload.php';
require_once('DBHandler.php');

use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\GetItemInput;
use AsyncAws\DynamoDb\Input\PutItemInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use Bref\Context\Context;
use Bref\Event\Handler;
use Dotenv\Dotenv;

class GetFromDb implements Handler
{
    public function handle($event, Context $context)
    {
        try {
            if ($_ENV['APP_ENV'] == 'local') {
                $dotenv = Dotenv::createImmutable(__DIR__);
                $dotenv->load();
            }

            $dynamoDb = new DynamoDbClient(DBHandler::setDB());

            $dynamoDb->putItem(new PutItemInput($this->createCepEntity($event['cep'])));

            $result = $dynamoDb->getItem(new GetItemInput([
                'TableName' => 'cep-table',
                'ConsistentRead' => true,
                'Key' => [
                    'cep' => new AttributeValue(['S' => $event['cep']])
                ],
            ]));

            return $this->parseResponse($result);
        } catch (\Throwable $exception) {
            return ['statusCode' => 404, 'body' => $exception->getMessage()];
        }
    }

    private function createCepEntity($cep): array
    {
        return [
            'TableName' => 'cep-table',
            'Item' => [
                'cep' => new AttributeValue(['S' => $cep]),
                'bairro' => new AttributeValue(['S' => 'guarani']),
                'cidade' => new AttributeValue(['S' => 'novo hamburgo']),
                'estado' => new AttributeValue(['S' => 'RS']),
                'street' => new AttributeValue(['S' => 'jose joao martins']),
                'created_at' => new AttributeValue(['S' => '2023-07-23 09:23:32']),
                'updated_at' => new AttributeValue(['S' => '2023-07-23 09:23:32']),
            ],
        ];
    }

    private function parseResponse($response): array
    {
        return [
            "cep" => $response->getItem()['cep']->getS(),
            "bairro" => $response->getItem()['bairro']->getS(),
            "cidade" => $response->getItem()['cidade']->getS(),
            "estado" => $response->getItem()['estado']->getS(),
            "street" => $response->getItem()['street']->getS(),
            "created_at" => $response->getItem()['created_at']->getS()
        ];
    }
}

return new GetFromDb();

?>