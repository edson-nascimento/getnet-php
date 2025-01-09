<?php

namespace Tests;

use Getnet\API\AuthorizeResponse;
use Getnet\API\Card;
use Getnet\API\Transaction;
use PHPUnit\Framework\Attributes\Group;

final class AuthorizeDebitTest extends TestBase
{
    #[Group('e2e')]
    public function testAuthorizeDebitCreate(): AuthorizeResponse
    {
        $transaction = $this->generateMockTransaction();
        $transaction->setAmount(8579.96);

        // Generate token card
        $tokenCard = new \Getnet\API\Token('5155901222280001', $transaction->getCustomer()->getCustomerId(), $this->getnetService());

        // Add payment
        $transaction->debit()
            ->setCardholderMobile('5551999887766')
            ->setDynamicMcc('1799')
            ->setSoftDescriptor('LOJA*TESTE*COMPRA-123')
            ->card($tokenCard)
            ->setBrand(Card::BRAND_MASTERCARD)
            ->setExpirationMonth('12')
            ->setExpirationYear(date('y') + 1)
            ->setCardholderName('Jax Teller')
            ->setSecurityCode('123');

        $response = $this->getnetService()->authorize($transaction);

        if (!($response instanceof AuthorizeResponse)) {
            throw new \Exception($response->getResponseJSON());
        }

        $this->assertSame(Transaction::STATUS_PENDING, $response->getStatus(), $response->getResponseJSON());
        $this->assertNotEmpty($response->getPaymentId());
        $this->assertNotEmpty($response->getPayerAuthenticationRequest());
        $this->assertNotEmpty($response->getRedirectUrl());
        $this->assertNotEmpty(filter_var($response->getRedirectUrl(), FILTER_VALIDATE_URL));

        return $response;
    }

    // TODO complete tests in examples/Debit.php
}
