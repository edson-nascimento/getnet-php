<?php

require_once '../config/bootstrap.test.php';

// Autenticação da API
$getnet = getnetServiceTest();

// List customers
$response = $getnet->customRequest('GET', '/v1/customers?page=1&limit=5');
print_r($response['customers']);

// Crerate payment link
$linkResponse = $getnet->customRequest('POST', '/v1/payment-links', [
    'label' => 'carrinho1',
    'expiration' => '2028-12-30T13:18:27.232Z',
    'max_orders' => 1,
    'order' => [
        'product_type' => 'cash_carry',
        'title' => 'test product',
        'description' => 'description test',
        'order_prefix' => 'test',
        'shipping_amount' => 0,
        'amount' => 10000,
    ],
    'payment' => [
        'credit' => [
            'enable' => true,
            'max_installments' => 5,
        ],
        'debit' => [
            'enable' => false,
        ],
    ],
]);

print_r($linkResponse);

// Get payment link
$link = $getnet->customRequest('GET', "/v1/payment-links/{$linkResponse['link_id']}");
print_r($link);

// See all in https://developers.getnet.com.br/api#tag/GetPay
