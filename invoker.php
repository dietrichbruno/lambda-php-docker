<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$lambda = new AsyncAws\Lambda\LambdaClient([
    'accessKeyId' => $_ENV['AWS_KEY'],
    'accessKeySecret' => $_ENV['AWS_SECRET'],
    'region' => $_ENV['AWS_REGION'],
]);

$result = $lambda->invoke([
    'FunctionName' => 'app-dev-api',
    'Payload' => json_encode(['value' => 'test']),
]);

var_dump($result->getPayload());