<?php

namespace Tests;

use PHPUnit\Framework\Attributes\Group;

final class AppTest extends TestBase
{
    #[Group('e2e')]
    public function testCredentials(): void
    {
        $this->assertInstanceOf(\Getnet\API\Getnet::class, $this->getnetService());
    }
}
