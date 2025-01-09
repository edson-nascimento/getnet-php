<?php

namespace Tests;

use PHPUnit\Framework\Attributes\Group;

final class CustomRequestTest extends TestBase
{
    #[Group('e2e')]
    public function testListCustomers(): void
    {
        $response = $this->getnetService()->customRequest('GET', '/v1/customers?page=1&limit=5');

        $this->assertIsArray($response);
        $this->assertArrayHasKey('customers', $response);
    }
}
