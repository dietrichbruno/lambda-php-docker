<?php

use Bref\Context\Context;

require_once('GetFromDb.php');
require __DIR__ . '/vendor/autoload.php';

return function($event, Context $context) {
    $getFromDb = new GetFromDb();

    return $getFromDb->handle(["cep" => "93520370"], $context);
}

?>