<?php

use Getnet\API\Card;

require_once '../config/bootstrap.test.php';

// Autenticação da API
$getnet = getnetServiceTest();

$cardService = new \Getnet\API\Service\CardService($getnet);

$card_number = '5155901222280001';
$customer_id = 'customer_210818263';

// Generate token
$cardToken = $cardService->generateCardToken($card_number, $customer_id);
var_dump($cardToken->getNumberToken());

$card = new Card($cardToken);
$card->setBrand(Card::BRAND_MASTERCARD)
    ->setExpirationMonth('12')
    ->setExpirationYear(date('y') + 1)
    ->setCardholderName('Jax Teller')
    ->setSecurityCode('123')
    ->setCustomerId($customer_id);

// Save
$tokenResponse = $cardService->saveCard($card);
var_dump($tokenResponse->getCardId());

// Get by card_id
$savedCard = $cardService->getCard($tokenResponse->getCardId());
var_dump($savedCard);

// Get by customer_id
$cards = $cardService->getCardsByCustomerId($customer_id);
var_dump($cards);

// Delete
$delete = $cardService->deleteCard($tokenResponse->getCardId());
var_dump($delete);
