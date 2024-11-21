<?php
namespace Getnet\API;

/**
 * Class Transaction
 *
 * @package Getnet\API
 */
class Transaction implements \JsonSerializable
{
    use TraitEntity;

    const STATUS_AUTHORIZED = "AUTHORIZED";

    const STATUS_CONFIRMED = "CONFIRMED";

    const STATUS_PENDING = "PENDING";

    const STATUS_WAITING = "WAITING";

    const STATUS_APPROVED = "APPROVED";

    const STATUS_CANCELED = "CANCELED";

    const STATUS_DENIED = "DENIED";

    const STATUS_ERROR = "ERROR";

    private $seller_id;

    private $amount;

    private $currency = "BRL";

    private $order;

    private $customer;

    private $device;

    private $shippings;

    private $credit;

    private $debit;

    private $boleto;

    private $tokenization;

    /**
     *
     * @return mixed
     */
    public function getSellerId()
    {
        return $this->seller_id;
    }

    /**
     *
     * @param mixed $seller_id
     */
    public function setSellerId($seller_id)
    {
        $this->seller_id = (string) $seller_id;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     *
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = (int) (string) ($amount * 100);

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     *
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = (string) $currency;

        return $this;
    }

    /**
     *
     * @param string|null $order_id
     * @return Order
     */
    public function order($order_id = null)
    {
        $order = new Order($order_id);
        $this->setOrder($order);

        return $order;
    }

    /**
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     *
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     *
     * @param mixed $id
     * @return Customer
     */
    public function customer($id = null)
    {
        $customer = new Customer($id);

        $this->setCustomer($customer);

        return $customer;
    }

    /**
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     *
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     *
     * @param mixed $device_id
     * @return Device
     */
    public function device($device_id)
    {
        $device = new Device($device_id);

        $this->device = $device;

        return $device;
    }

    /**
     *
     * @return Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     *
     * @param Device $device
     */
    public function setDevice(Device $device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getShippings()
    {
        return $this->shippings;
    }

    /**
     *
     * @param array $shippings
     */
    public function setShippings($shippings)
    {
        $this->shippings = $shippings;

        return $this;
    }

    /**
     *
     * @return Shipping
     */
    public function shipping()
    {
        $shipping = new Shipping();

        $this->addShipping($shipping);

        return $shipping;
    }

    /**
     *
     * @param Shipping $shipping
     */
    public function addShipping(Shipping $shipping)
    {
        if (! is_array($this->shippings)) {
            $this->shippings = array();
        }

        $this->shippings[] = $shipping;
    }

    /**
     *
     * @param Customer $customer
     * @return Shipping
     */
    public function addShippingByCustomer(Customer $customer)
    {
        $shipping = new Shipping();

        $this->addShipping($shipping->populateByCustomer($customer));

        return $shipping;
    }

    /**
     *
     * @return Credit
     */
    public function credit()
    {
        $credit = new Credit();
        $this->setCredit($credit);

        return $credit;
    }

    /**
     *
     * @return Credit|null
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     *
     * @param Credit $credit
     */
    public function setCredit(Credit $credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     *
     * @return Credit
     */
    public function debit()
    {
        $debit = new Credit();

        $this->setDebit($debit);

        return $debit;
    }

    /**
     *
     * @return Credit|null
     */
    public function getDebit()
    {
        return $this->debit;
    }

    /**
     *
     * @param Credit $debit
     */
    public function setDebit(Credit $debit)
    {
        $this->debit = $debit;

        return $this;
    }

    /**
     *
     * @param string|null $our_number
     * @return Boleto
     */
    public function boleto($our_number = null)
    {
        $boleto = new Boleto($our_number);
        $this->boleto = $boleto;

        return $boleto;
    }

    /**
     *
     * @return Boleto|null
     */
    public function getBoleto()
    {
        return $this->boleto;
    }

    /**
     *
     * @param Boleto $boleto
     */
    public function setBoleto(Boleto $boleto)
    {
        $this->boleto = $boleto;

        return $this;
    }

    /**
     *
     * @return Tokenization
     */
    public function tokenization()
    {
        $tokenization = new Tokenization();
        $this->setTokenization($tokenization);

        return $tokenization;
    }

    /**
     *
     * @return Tokenization|null
     */
    public function getTokenization()
    {
        return $this->tokenization;
    }

    /**
     *
     * @param Tokenization $tokenization
     */
    public function setTokenization(Tokenization $tokenization)
    {
        $this->tokenization = $tokenization;

        return $this;
    }
}
