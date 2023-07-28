<?php

use AsyncAws\Lambda\LambdaClient;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$credentials = [
    'accessKeyId' => $_ENV['AWS_KEY'],
    'accessKeySecret' => $_ENV['AWS_SECRET'],
    'region' => $_ENV['AWS_REGION'],
];

$lambda = new LambdaClient($credentials);

$result = $lambda->invoke([
    'FunctionName' => 'app-dev-api',
    'Payload' => json_encode(['cep' => '93310201']),
]);

return $result;

?>