<?php

namespace Getnet\API;

class Customer implements \JsonSerializable
{
    use TraitEntity;

    public const DOCUMENT_TYPE_CPF = 'CPF';

    public const DOCUMENT_TYPE_CNPJ = 'CNPJ';

    private $customer_id;

    private $first_name;

    private $last_name;

    private $name;

    private $email;

    private $document_type;

    private $document_number;

    private $phone_number;

    private $billing_address;

    public function __construct($customer_id = null)
    {
        $this->setCustomerId($customer_id);
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = (string) $customer_id;

        return $this;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = (string) $first_name;

        return $this;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setLastName($last_name)
    {
        $this->last_name = (string) $last_name;

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

    public function getDocumentType()
    {
        return $this->document_type;
    }

    public function setDocumentType($document_type)
    {
        $this->document_type = (string) $document_type;

        return $this;
    }

    public function getDocumentNumber()
    {
        return $this->document_number;
    }

    public function setDocumentNumber($document_number)
    {
        $this->document_number = (string) $document_number;

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

    /**
     * @return Address
     */
    public function billingAddress()
    {
        $address = new Address();

        $this->setBillingAddress($address);

        return $address;
    }

    /**
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billing_address;
    }

    public function setBillingAddress($billing_address)
    {
        $this->billing_address = $billing_address;

        return $this;
    }
}
