<?php
use Getnet\API\PixTransaction;

require_once '../config/bootstrap.test.php';

//Autenticação da API
$getnet = getnetServiceTest();

//Cria a transação
$transaction = new PixTransaction(75.50);
$transaction->setCurrency("BRL");
$transaction->setOrderId('DEV-1608748980');
$transaction->setCustomerId('12345');

// Opcional, o valor default de 180s. O valor máximo para o tempo de expiração é 1800s
$transaction->setExpirationTime(600);

$response = $getnet->pix($transaction);

var_dump($response);