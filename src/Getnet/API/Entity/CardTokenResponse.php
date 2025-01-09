<?php

namespace Getnet\API\Entity;

class CardTokenResponse
{
    private $card_id;

    private $number_token;

    public function __construct(string $card_id, string $number_token)
    {
        $this->card_id = $card_id;
        $this->number_token = $number_token;
    }

    public function getCardId()
    {
        return $this->card_id;
    }

    public function getNumberToken()
    {
        return $this->number_token;
    }
}
