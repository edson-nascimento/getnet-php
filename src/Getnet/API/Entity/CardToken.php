<?php

namespace Getnet\API\Entity;

use Getnet\API\Token;

class CardToken extends Token
{
    public function __construct(string $number_token, ?string $customer_id = null)
    {
        $this->number_token = $number_token;
        $this->customer_id = $customer_id;
    }
}
