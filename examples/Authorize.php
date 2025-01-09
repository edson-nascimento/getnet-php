<?php

use Getnet\API\Card;
use Getnet\API\Credit;
use Getnet\API\Customer;
use Getnet\API\Order;
use Getnet\API\Token;
use Getnet\API\Transaction;

require_once '../config/bootstrap.test.php';

// Autenticação da API
$getnet = getnetServiceTest();

// Cria a transação
$transaction = new Transaction();
$transaction->setSellerId($getnet->getSellerId());
$transaction->setCurrency('BRL');
$transaction->setAmount(27.50);

// Adicionar dados do Pedido
$transaction->order('123456')
->setProductType(Order::PRODUCT_TYPE_SERVICE)
->setSalesTax(0);

// Criar token Cartão
$tokenCard = new Token('5155901222280001', 'customer_210818263', $getnet);

// Adicionar dados do Pagamento
$transaction->credit()
            ->setAuthenticated(false)
            ->setDynamicMcc('1799')
            ->setSoftDescriptor('LOJA*TESTE*COMPRA-123')
            ->setDelayed(false)
            ->setPreAuthorization(false)
            ->setNumberInstallments(2)
            ->setSaveCardData(false)
            ->setTransactionType(Credit::TRANSACTION_TYPE_INSTALL_NO_INTEREST)
            ->card($tokenCard)
                ->setBrand(Card::BRAND_MASTERCARD)
                ->setExpirationMonth('12')
                ->setExpirationYear(date('y') + 1)
                ->setCardholderName('Jax Teller')
                ->setSecurityCode('123');

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

// Adicionar dados de entrega
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

// Ou pode adicionar entrega com os mesmos dados do customer
// $transaction->addShippingByCustomer($transaction->getCustomer())->setShippingAmount(0);

// Adiciona o dispositivo
$transaction->device('device_id')->setIpAddress('127.0.0.1');

$response = $getnet->authorize($transaction);

print_r($response->getStatus() . "\n");

// ## CANCELA PAGAMENTO (CANCEL)
$capture = $getnet->authorizeCancel($response->getPaymentId(), $response->getAmount());
print_r($capture->getStatus() . "\n");
