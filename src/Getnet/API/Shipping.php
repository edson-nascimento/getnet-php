<?php

namespace Getnet\API;

class Shipping implements \JsonSerializable
{
    use TraitEntity;

    private $first_name;

    private $name;

    private $email;

    private $phone_number;

    private $shipping_amount = 0;

    private $address;

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = (string) $first_name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = (string) $email;

        return $this;
    }

    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = (string) $phone_number;

        return $this;
    }

    public function getShippingAmount()
    {
        return $this->shipping_amount;
    }

    public function setShippingAmount($shipping_amount)
    {
        $this->shipping_amount = (int) (string) ($shipping_amount * 100);

        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress(Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Address
     */
    public function address()
    {
        $address = new Address();

        $this->setAddress($address);

        return $address;
    }

    /**
     * @return Shipping
     */
    public function populateByCustomer(Customer $customer)
    {
        $this->setFirstName($customer->getFirstName());
        $this->setName($customer->getName());
        $this->setEmail($customer->getEmail());
        $this->setPhoneNumber($customer->getPhoneNumber());
        $this->setAddress($customer->getBillingAddress());

        return $this;
    }
}
