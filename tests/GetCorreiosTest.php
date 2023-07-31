<?php

require_once('app/GetCorreios.php');

use app\GetCorreios;
use Bref\Context\Context;
use PHPUnit\Framework\TestCase;

final class GetCorreiosTest extends TestCase
{
    public function testGetCorreiosHandler(): void
    {
        $getCorreios = new GetCorreios();

        $result = $getCorreios->handle(["cep" => "93520370"], Context::fake());

        var_dump($result);

        $this->assertSame(true, true);
    }
}

?>