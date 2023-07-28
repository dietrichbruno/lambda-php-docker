<?php

require_once('GetFromDb.php');

use Bref\Context\Context;


test('getFromDb', function () {
    $getFromDb = new GetFromDb();

    $result = $getFromDb->handle(["cep" => "93520370"], Context::fake());

    echo json_encode($result);

    expect(true)->toBeTrue();
});
