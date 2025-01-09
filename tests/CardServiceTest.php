<?php

namespace Tests;

use Getnet\API\Card;
use Getnet\API\Token;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Group;

final class CardServiceTest extends TestBase
{
    #[Group('e2e')]
    public function testCardToken(): Token
    {
        $cardService = new \Getnet\API\Service\CardService($this->getnetService());

        $card_number = '5155901222280001';
        $customer_id = 'customer_210818263';
        $cardToken = $cardService->generateCardToken($card_number, $customer_id);

        $this->assertIsObject($cardToken);
        $this->assertIsString($cardToken->getNumberToken());

        return $cardToken;
    }

    #[Group('e2e')]
    #[Depends('testCardToken')]
    public function testSaveCard(Token $cardToken): void
    {
        $cardService = new \Getnet\API\Service\CardService($this->getnetService());

        $card = new Card($cardToken);
        $card->setBrand(Card::BRAND_MASTERCARD)
            ->setExpirationMonth('12')
            ->setExpirationYear(date('y') + 1)
            ->setCardholderName('Jax Teller')
            ->setSecurityCode('123')
            ->setCustomerId($cardToken->getCustomerId());

        $tokenResponse = $cardService->saveCard($card);

        $this->assertIsObject($tokenResponse);
        $this->assertSame($cardToken->getNumberToken(), $tokenResponse->getNumberToken());
        $this->assertIsString($tokenResponse->getCardId());

        $savedCard = $cardService->getCard($tokenResponse->getCardId());

        $this->assertIsObject($savedCard);
        $this->assertSame($card->getNumberToken(), $savedCard->getNumberToken());
        $this->assertSame($card->getBrand(), $savedCard->getBrand());
        $this->assertSame($card->getCardholderName(), $savedCard->getCardholderName());
        $this->assertSame($card->getExpirationMonth(), $savedCard->getExpirationMonth());
        $this->assertSame($card->getExpirationYear(), $savedCard->getExpirationYear());
        $this->assertNull($savedCard->getSecurityCode());
    }
}
