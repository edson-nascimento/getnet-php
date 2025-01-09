<?php

namespace Getnet\API;

class PixTransaction implements \JsonSerializable
{
    use TraitEntity;

    private $amount;

    private $currency = 'BRL';

    private $order_id;

    private $customer_id;

    // In seconds, between 180 and 1800
    private ?int $expiration_time = null;

    public function __construct($amount = null)
    {
        if (!is_null($amount)) {
            $this->setAmount($amount);
        }
    }

    public function beforeSerialize()
    {
        $this->expiration_time = null;
    }

    // gets and sets
    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = (int) (string) ($amount * 100);

        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getExpirationTime(): ?int
    {
        return $this->expiration_time;
    }

    public function setExpirationTime(?int $expiration_time)
    {
        $this->expiration_time = $expiration_time;

        return $this;
    }
}
