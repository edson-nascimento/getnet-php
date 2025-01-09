<?php

namespace Tests;

use Getnet\API\AuthorizeResponse;
use Getnet\API\Card;
use Getnet\API\Credit;
use Getnet\API\Transaction;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Group;

final class AuthorizePosteriorTest extends TestBase
{
    #[Group('e2e')]
    public function testAuthorizePosteriorCreate(): AuthorizeResponse
    {
        $transaction = $this->generateMockTransaction();
        $transaction->setAmount(27.90);

        // Generate token card
        $tokenCard = new \Getnet\API\Token('5155901222280001', $transaction->getCustomer()->getCustomerId(), $this->getnetService());

        // Add payment
        $transaction->credit()
            ->setAuthenticated(false)
            ->setDynamicMcc('1799')
            ->setSoftDescriptor('LOJA*TESTE*COMPRA-123')
            ->setDelayed(false)
            ->setPreAuthorization(true)
            ->setNumberInstallments(2)
            ->setSaveCardData(false)
            ->setTransactionType(Credit::TRANSACTION_TYPE_INSTALL_NO_INTEREST)
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

        $this->assertSame(Transaction::STATUS_AUTHORIZED, $response->getStatus(), $response->getResponseJSON());
        $this->assertSame($transaction->getAmount(), $response->getAmount());
        $this->assertSame($transaction->getOrder()->getOrderId(), $response->getOrderId());
        $this->assertNotEmpty($response->getPaymentId());

        return $response;
    }

    #[Group('e2e')]
    #[Depends('testAuthorizePosteriorCreate')]
    public function testAuthorizePosteriorCapture(AuthorizeResponse $response): void
    {
        $result = $this->getnetService()->authorizeConfirm($response->getPaymentId(), $response->getAmount());

        $this->assertInstanceOf(AuthorizeResponse::class, $result);
        $this->assertSame(Transaction::STATUS_CONFIRMED, $result->getStatus(), $response->getResponseJSON());
        $this->assertSame($response->getAmount(), $result->getAmount());
        $this->assertSame($response->getPaymentId(), $result->getPaymentId());
    }
}
