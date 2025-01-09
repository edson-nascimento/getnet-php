<?php

namespace Tests;

use Getnet\API\PixResponse;
use Getnet\API\PixTransaction;
use Getnet\API\Transaction;
use PHPUnit\Framework\Attributes\Group;

final class PixTest extends TestBase
{
    #[Group('e2e')]
    public function testPixCreate(): PixResponse
    {
        $transaction = new PixTransaction(20597.75);
        $transaction->setCurrency('BRL');
        $transaction->setOrderId('DEV-1608748980');
        $transaction->setCustomerId('12345');

        $response = $this->getnetService()->pix($transaction);

        if (!($response instanceof PixResponse)) {
            throw new \Exception($response->getResponseJSON());
        }

        $this->assertSame(Transaction::STATUS_WAITING, $response->getStatus(), $response->getResponseJSON());
        $this->assertSame($transaction->getOrderId(), $response->getOrderId());
        $this->assertNotEmpty($response->getQrCode());
        $this->assertNotEmpty($response->getExpirationDateQrcode());
        $this->assertNotEmpty($response->getTransactionId());

        return $response;
    }
}
