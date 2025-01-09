<?php

namespace Getnet\API\Service;

use Getnet\API\Card;
use Getnet\API\Entity\CardToken;
use Getnet\API\Entity\CardTokenResponse;
use Getnet\API\Exception\GetnetException;

class CardService extends BaseService
{
    public function generateCardToken(string $card_number, string $customer_id): ?CardToken
    {
        $response = $this->getnetService()->customRequest('POST', '/v1/tokens/card', [
            'card_number' => $card_number,
            'customer_id' => $customer_id,
        ]);

        if (is_array($response) && isset($response['number_token'])) {
            return new CardToken($response['number_token'], $customer_id);
        }

        return null;
    }

    public function saveCard(Card $card): CardTokenResponse
    {
        $response = $this->getnetService()->customRequest('POST', '/v1/cards', $card->toArray());

        if (is_array($response) && isset($response['card_id'])) {
            return new CardTokenResponse($response['card_id'], $response['number_token']);
        }

        throw new GetnetException('Error on saveCard');
    }

    /**
     * @return Card[]
     */
    public function getCardsByCustomerId(string $customer_id): array
    {
        try {
            $response = $this->getnetService()->customRequest('GET', "/v1/cards?customer_id={$customer_id}");

            if (is_array($response) && isset($response['cards'])) {
                return array_map(function (array $cardResponde) {
                    return (new Card())->populateByArray($cardResponde);
                }, $response['cards']);
            }
        } catch (GetnetException $e) {
            // If not found throws Exception
            return [];
        }

        return [];
    }

    public function getCard(string $card_id): ?Card
    {
        try {
            $response = $this->getnetService()->customRequest('GET', "/v1/cards/{$card_id}");

            if (is_array($response) && isset($response['card_id'])) {
                return (new Card())->populateByArray($response);
            }
        } catch (GetnetException $e) {
            // If not found throws Exception
            return null;
        }

        return null;
    }

    public function deleteCard(string $card_id): bool
    {
        $response = $this->getnetService()->customRequest('DELETE', "/v1/cards/{$card_id}");

        if (is_array($response) && isset($response['status_code'])) {
            return $response['status_code'] === 204;
        }

        return false;
    }
}
