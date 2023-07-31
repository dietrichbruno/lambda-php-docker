<?php

require __DIR__ . '/vendor/autoload.php';
require_once('DBHandler.php');

use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\PutItemInput;
use Bref\Context\Context;
use Bref\Event\Handler;
use Dotenv\Dotenv;
use Symfony\Component\HttpClient\HttpClient;
use Carbon\Carbon;

class GetCorreios implements Handler
{
    private DynamoDbClient $dynamoDb;

    public function handle($event, Context $context)
    {
        try {
            $this->loadEnv();

            $this->dynamoDb = new DynamoDbClient(DBHandler::getDBConfig());
            $result = $this->dynamoDb->getItem(DBHandler::getCorreiosSessionItem('production'));

            $token = $this->manageToken($result);

            // get cep from correios

            return [$token];
        } catch (\Throwable $exception) {
            return ['statusCode' => 404, 'body' => $exception->getMessage()];
        }
    }

    private function loadEnv(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }

    private function isExpiredToken($result): bool
    {
        if (empty($result->getItem('expires_at'))) {
            return true;
        }

        return Carbon::create(
            $result->getItem()['expires_at']->getS())
            ->subMinutes(15)
            ->lt(Carbon::now()->toString()
        );
    }

    private function checkCorreiosSession()
    {
        $client = HttpClient::create([
            'headers' => [
                "Content-Type" => "application/json",
                "Accept" => "application/json",
                "Authorization" => "Basic " . $_ENV['CORREIOS_API_CODE']
            ],
        ]);

        $response = $client->request(
            'POST',
            'https://api.correios.com.br/token/v1/autentica/cartaopostagem',
            [
                'json' => [
                    'numero' => $_ENV['CORREIOS_POSTCARD_NUMBER']
                ]
            ]
        );

        $this->dynamoDb->putItem(new PutItemInput(
                DBHandler::createCorreiosSessionEntity(
                    $response->toArray())
            )
        );

        return $response->toArray()['token'];
    }

    private function manageToken(\AsyncAws\DynamoDb\Result\GetItemOutput $result)
    {
        if ($this->isExpiredToken($result)) {
            return $this->checkCorreiosSession();
        }

        return $result->getItem()['token']->getS();
    }
}

return new GetCorreios();

?>