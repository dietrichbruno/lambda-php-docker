<?php

use AsyncAws\SimpleS3\SimpleS3Client;
use Symfony\Component\HttpClient\HttpClient;
use Bref\Context\Context;

require __DIR__ . '/vendor/autoload.php';

return function($event, Context $context) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();

        $client = HttpClient::create();

        if (getEnv('APP_ENV') != 'local') {
            $response = $client->request(
                'GET',
                'http://localhost:2773/secretsmanager/get?secretId=lambda-secret',
                [
                    'headers' => [
                        'X-Aws-Parameters-Secrets-Token' => getEnv('AWS_SESSION_TOKEN')
                    ]
                ]
            );

            $secrets = json_decode($response->toArray()['SecretString']);
            $_ENV['AWS_KEY'] = $secrets->access_key;
            $_ENV['AWS_SECRET'] = $secrets->access_secret;
        }

        $s3 = new SimpleS3Client([
            'region' => 'us-east-1',
            'accessKeyId' => $_ENV['AWS_KEY'],
            'accessKeySecret' => $_ENV['AWS_SECRET']
        ]);

        $s3->upload('lambda-test-bruno', 'photos/cat_2.txt', 'I like this cat');

        $url = $s3->getUrl('my-image-bucket', 'photos/cat_2.jpg');

        echo $url;

        return $url;
    } catch (\Exception $exception) {
        return ['statusCode' => 404, 'body' => $exception->getMessage()];
    }
}

?>