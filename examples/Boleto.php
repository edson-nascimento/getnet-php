<?php

use Getnet\API\Boleto;
use Getnet\API\Customer;
use Getnet\API\Order;
use Getnet\API\Transaction;

require_once '../config/bootstrap.test.php';

// Autenticação da API
$getnet = getnetServiceTest();

// Cria a transação
$transaction = new Transaction();
$transaction->setSellerId($getnet->getSellerId());
$transaction->setCurrency('BRL');
$transaction->setAmount(75.50);

// Adicionar dados do Pedido
$transaction->order('123456')
->setProductType(Order::PRODUCT_TYPE_SERVICE)
->setSalesTax(0);

$transaction->boleto('000001946598')
            ->setDocumentNumber('170500000019763')
            ->setExpirationDate(date('d/m/Y', strtotime('+2 days')))
            ->setProvider(Boleto::PROVIDER_SANTANDER)
            ->setInstructions('Não receber após o vencimento');

// Adicionar dados do cliente
$transaction->customer('customer_210818263')
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

$response = $getnet->boleto($transaction);

print_r($response->getStatus() . "\n");

if ($response instanceof \Getnet\API\BoletoRespose) {
    print_r($response->getBoletoHtml() . "\n");
}
