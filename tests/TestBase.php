<?php

namespace Tests;

use Getnet\API\Customer;
use Getnet\API\Getnet;
use Getnet\API\Order;
use Getnet\API\Transaction;
use PHPUnit\Framework\TestCase;

abstract class TestBase extends TestCase
{
    protected static $service;

    protected function getnetService(): Getnet
    {
        if (!self::$service) {
            self::$service = getnetServiceTest();
        }

        return self::$service;
    }

    protected function generateMockTransaction(bool $shipping = true, ?string $order_id = null, ?string $customer_id = null): Transaction
    {
        $transaction = new Transaction();
        $transaction->setSellerId($this->getnetService()
            ->getSellerId());
        $transaction->setCurrency('BRL');
        $transaction->setAmount(1599.50);

        // Add order
        $transaction->order($order_id ?? '123456')
            ->setProductType(Order::PRODUCT_TYPE_SERVICE)
            ->setSalesTax(0);

        // Add customer
        $transaction->customer($customer_id ?? '123456')
            ->setDocumentType(Customer::DOCUMENT_TYPE_CPF)
            ->setEmail('customer@email.com.br')
            ->setFirstName('Jax')
            ->setLastName('Teller')
            ->setName('Jax Teller')
            ->setPhoneNumber('5551999887766')
            ->setDocumentNumber('12345678912')
            ->billingAddress()
                ->setCity('São Paulo')
                ->setComplement('Sons of Anarchy')
                ->setCountry('Brasil')
                ->setDistrict('Centro')
                ->setNumber('1000')
                ->setPostalCode('90230060')
                ->setState('SP')
                ->setStreet('Av. Brasil');

        // Add shipping
        if ($shipping) {
            $transaction->shipping()
                ->setFirstName('Jax')
                ->setEmail('customer@email.com.br')
                ->setName('Jax Teller')
                ->setPhoneNumber('5551999887766')
                ->setShippingAmount(0)
                ->address()
                    ->setCity('Porto Alegre')
                    ->setComplement('Sons of Anarchy')
                    ->setCountry('Brasil')
                    ->setDistrict('São Geraldo')
                    ->setNumber('1000')
                    ->setPostalCode('90230060')
                    ->setState('RS')
                    ->setStreet('Av. Brasil');
        }

        return $transaction;
    }
}
