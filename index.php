<?php

use Bref\Context\Context;

require_once('GetCorreios.php');
require __DIR__ . '/vendor/autoload.php';

return function($event, Context $context) {
    $getCorreios = new GetCorreios();

    return $getCorreios->handle(["cep" => "93520370"], $context);
}

?>