<?php

namespace Getnet\API;

class Card implements \JsonSerializable
{
    use TraitEntity;

    public const BRAND_MASTERCARD = 'Mastercard';

    public const BRAND_VISA = 'Visa';

    public const BRAND_AMEX = 'Amex';

    public const BRAND_ELO = 'Elo';

    public const BRAND_HIPERCARD = 'Hipercard';

    private $brand;

    private $cardholder_name;

    private $expiration_month;

    private $expiration_year;

    private $number_token;

    private $security_code;

    // Cofre fields
    private $customer_id;

    private $cardholder_identification;

    private $verify_card;

    private $card_id;

    private $last_four_digits;

    private $bin;

    private $status;

    private $transaction_id;

    private $used_at;

    private $created_at;

    private $updated_at;

    /**
     * Card constructor.
     */
    public function __construct(?Token $token = null)
    {
        if ($token) {
            $this->setNumberToken($token);
        }
    }

    // Hidden info from saved card
    public function beforeSerialize()
    {
        $this->customer_id = null;
        $this->cardholder_identification = null;
        $this->verify_card = null;
        $this->card_id = null;
        $this->last_four_digits = null;
        $this->bin = null;
        $this->status = null;
        $this->transaction_id = null;
        $this->used_at = null;
        $this->created_at = null;
        $this->updated_at = null;
    }

    // Gets and sets

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($brand)
    {
        $this->brand = (string) $brand;

        return $this;
    }

    public function getCardholderName()
    {
        return $this->cardholder_name;
    }

    public function setCardholderName($cardholder_name)
    {
        $this->cardholder_name = (string) $cardholder_name;

        return $this;
    }

    public function getExpirationMonth()
    {
        return $this->expiration_month;
    }

    public function setExpirationMonth($expiration_month)
    {
        $this->expiration_month = (string) $expiration_month;

        return $this;
    }

    public function getExpirationYear()
    {
        return $this->expiration_year;
    }

    public function setExpirationYear($expiration_year)
    {
        $this->expiration_year = (string) $expiration_year;

        return $this;
    }

    public function getNumberToken()
    {
        return $this->number_token;
    }

    // TODO maybe remove entity Token.
    public function setNumberToken(Token $token)
    {
        $this->number_token = (string) $token->getNumberToken();

        return $this;
    }

    public function getSecurityCode()
    {
        return $this->security_code;
    }

    public function setSecurityCode($security_code)
    {
        $this->security_code = (string) $security_code;

        return $this;
    }

    // Cofre fields
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getCardholderIdentification()
    {
        return $this->cardholder_identification;
    }

    public function setCardholderIdentification($cardholder_identification)
    {
        $this->cardholder_identification = $cardholder_identification;

        return $this;
    }

    public function getVerifyCard()
    {
        return $this->verify_card;
    }

    /**
     * @param bool $verify_card
     */
    public function setVerifyCard($verify_card)
    {
        $this->verify_card = $verify_card;

        return $this;
    }

    public function getCardId()
    {
        return $this->card_id;
    }

    public function getLastFourDigits()
    {
        return $this->last_four_digits;
    }

    public function getBin()
    {
        return $this->bin;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    public function getUsedAt()
    {
        return $this->used_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
