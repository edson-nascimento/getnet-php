<?php

namespace Getnet\API;

class Transaction implements \JsonSerializable
{
    use TraitEntity;

    public const STATUS_AUTHORIZED = 'AUTHORIZED';

    public const STATUS_CONFIRMED = 'CONFIRMED';

    public const STATUS_PENDING = 'PENDING';

    public const STATUS_WAITING = 'WAITING';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_CANCELED = 'CANCELED';

    public const STATUS_DENIED = 'DENIED';

    public const STATUS_ERROR = 'ERROR';

    private $seller_id;

    private $amount;

    private $currency = 'BRL';

    private $order;

    private $customer;

    private $device;

    private $shippings;

    private $credit;

    private $debit;

    private $boleto;

    public function getSellerId()
    {
        return $this->seller_id;
    }

    public function setSellerId($seller_id)
    {
        $this->seller_id = (string) $seller_id;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = (int) (string) ($amount * 100);

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = (string) $currency;

        return $this;
    }

    /**
     * @param string|null $order_id
     *
     * @return Order
     */
    public function order($order_id = null)
    {
        $order = new Order($order_id);
        $this->setOrder($order);

        return $order;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return Customer
     */
    public function customer($id = null)
    {
        $customer = new Customer($id);

        $this->setCustomer($customer);

        return $customer;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Device
     */
    public function device($device_id)
    {
        $device = new Device($device_id);

        $this->device = $device;

        return $device;
    }

    /**
     * @return Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    public function setDevice(Device $device)
    {
        $this->device = $device;

        return $this;
    }

    public function getShippings()
    {
        return $this->shippings;
    }

    /**
     * @param array $shippings
     */
    public function setShippings($shippings)
    {
        $this->shippings = $shippings;

        return $this;
    }

    /**
     * @return Shipping
     */
    public function shipping()
    {
        $shipping = new Shipping();

        $this->addShipping($shipping);

        return $shipping;
    }

    public function addShipping(Shipping $shipping)
    {
        if (!is_array($this->shippings)) {
            $this->shippings = [];
        }

        $this->shippings[] = $shipping;
    }

    /**
     * @return Shipping
     */
    public function addShippingByCustomer(Customer $customer)
    {
        $shipping = new Shipping();

        $this->addShipping($shipping->populateByCustomer($customer));

        return $shipping;
    }

    /**
     * @return Credit
     */
    public function credit()
    {
        $credit = new Credit();
        $this->setCredit($credit);

        return $credit;
    }

    /**
     * @return Credit|null
     */
    public function getCredit()
    {
        return $this->credit;
    }

    public function setCredit(Credit $credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * @return Credit
     */
    public function debit()
    {
        $debit = new Credit();

        $this->setDebit($debit);

        return $debit;
    }

    /**
     * @return Credit|null
     */
    public function getDebit()
    {
        return $this->debit;
    }

    public function setDebit(Credit $debit)
    {
        $this->debit = $debit;

        return $this;
    }

    /**
     * @param string|null $our_number
     *
     * @return Boleto
     */
    public function boleto($our_number = null)
    {
        $boleto = new Boleto($our_number);
        $this->boleto = $boleto;

        return $boleto;
    }

    /**
     * @return Boleto|null
     */
    public function getBoleto()
    {
        return $this->boleto;
    }

    public function setBoleto(Boleto $boleto)
    {
        $this->boleto = $boleto;

        return $this;
    }
}
