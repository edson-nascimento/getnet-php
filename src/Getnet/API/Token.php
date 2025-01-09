<?php

namespace Getnet\API;

class Token
{
    public $number_token;

    protected $card_number;

    protected $customer_id;

    /**
     * Token constructor.
     *
     * @param string $card_number
     * @param string $customer_id
     */
    public function __construct($card_number, $customer_id, ?Getnet $credencial = null)
    {
        $this->setCardNumber($card_number);
        $this->setCustomerId($customer_id);

        if ($credencial) {
            $this->setNumberToken($credencial);
        }
    }

    public function __toString()
    {
        return $this->number_token;
    }

    public function getCardNumber()
    {
        return $this->card_number;
    }

    /**
     * @return Token
     */
    public function setCardNumber($card_number)
    {
        $this->card_number = (string) $card_number;

        return $this;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * @return Token
     */
    public function setCustomerId($customer_id)
    {
        $this->customer_id = (string) $customer_id;

        return $this;
    }

    public function getNumberToken()
    {
        return $this->number_token;
    }

    /**
     * @deprecated
     * Remove in next major version
     */
    public function setNumberToken(Getnet $credencial)
    {
        $data = [
            'card_number' => $this->card_number,
            'customer_id' => $this->customer_id,
        ];

        $request = new Request($credencial);
        $response = $request->post($credencial, '/v1/tokens/card', json_encode($data));
        $this->number_token = $response['number_token'];

        return $this;
    }
}
