#### Descrição
Adicionar uma nova funcionalidade ao projeto para implementar as rotinas de geração de token e criptograma de cartão, com foco no suporte completo ao protocolo de tokenização de bandeira Visa. Essa funcionalidade é essencial para aumentar a segurança nas transações de e-commerce, substituindo o número do cartão por um token exclusivo de bandeira combinado com um criptograma.

Importante: Identificamos que os testes para a tokenização da bandeira Visa ainda não estão presentes no projeto. A Visa tem incentivado a adoção dos tokens por estabelecimentos e passou a aplicar uma multa sobre o valor das transações realizadas sem token. Assim, a implementação dessa funcionalidade é prioritária para garantir conformidade com os padrões da bandeira e evitar custos adicionais para os clientes.

---

### **1. Geração do Token do Cartão**
#### Descrição
A rotina deve enviar os dados do cartão e do comprador para o endpoint `/v1/tokenization/token`, que retornará um token exclusivo associado ao cartão. 

#### Detalhes do Endpoint
**URL:** `/v1/tokenization/token`  
**Método:** `POST`  

**Request Body:**  
```json
{
  "customer_id": "customer_45678900, 123.456.789-00 ou 12345678900",
  "card_pan": "4622943120000493",
  "card_pan_source": "ON_FILE, MANUALLY_ENTERED ou VIA_APPLICATION",
  "card_brand": "VISA, MASTERCARD",
  "expiration_year": "2023",
  "expiration_month": "07",
  "security_code": 1234,
  "email": "tokenizacao_bandeira@getnet.com.br"
}
```

---

### **2. Geração do Criptograma do Cartão**
#### Descrição
A rotina deve enviar o token gerado previamente (`network_token_id`) e os dados adicionais da transação para o endpoint responsável por gerar o criptograma.

#### Detalhes do Endpoint
**URL:** `/v1/cryptogram/generate`  
**Método:** `POST`  

**Request Body:**  
```json
{
  "network_token_id": "1b110aaa71934ae492bff48baab9af81",
  "transaction_type": "CIT ou MIT",
  "cryptogram_type": "VISA_TAVV ou MC_DSRP_LONG",
  "amount": 1000,
  "customer_id": "customer_45678900",
  "email": "tokenizacao_bandeira@getnet.com.br",
  "card_brand": "VISA ou MASTERCARD"
}
```

#### Exemplo de transação
```php
<?php

use Getnet\API\Getnet;
use Getnet\API\Tokenization;
use Getnet\API\Transaction;
use Getnet\API\Environment;
use Getnet\API\Token;
use Getnet\API\BrandToken;
use Getnet\API\BrandCryptToken;
use Getnet\API\Credit;
use Getnet\API\Customer;
use Getnet\API\Card;
use Getnet\API\Order;

include 'vendor/autoload.php';

$client_id      = "xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxxxxxxx";
$client_secret  = "xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxxxxxxx";
$seller_id      = "xxxxxxxx-xxxx-xxxx-xxxxxxxxxxxxxxxxx";
$environment    = Environment::sandbox();

//Opicional, passar chave se você quiser guardar o token do auth na sessão para não precisar buscar a cada trasação, só quando expira
$keySession = null;

//Autenticação da API
$getnet = new Getnet($client_id, $client_secret, $environment, $keySession);

$brandToken = new BrandToken(
    "customer_210818263", 
    "customer@email.com.br", 
    "VISA",
    "MANUALLY_ENTERED",
    "4622943123039761",
    "722",
    "12",
    "2025",
    $getnet
);

$transaction = new Transaction();
$transaction->setSellerId($getnet->getSellerId());
$transaction->setCurrency("BRL");
$transaction->setAmount(103.03);

//Adicionar dados do Pedido
$transaction->order("123456")
    ->setProductType(Order::PRODUCT_TYPE_SERVICE)
    ->setSalesTax(0);

//Criar token Cartão
$tokenCard = new Token("4622943123039761", "customer_210818263", $getnet);


//Adicionar dados do Pagamento
$transaction->credit()
    ->setAuthenticated(false)
    ->setDynamicMcc("1799")
    ->setSoftDescriptor("LOJA*TESTE*COMPRA-123")
    ->setDelayed(false)
    ->setPreAuthorization(false)
    ->setNumberInstallments(3)
    ->setSaveCardData(false)
    ->setTransactionType(Credit::TRANSACTION_TYPE_INSTALL_WITH_INTEREST)
    ->card($tokenCard)
    ->setBrand(Card::BRAND_MASTERCARD)
    ->setExpirationMonth("12")
    ->setExpirationYear(date('y')+1)
    ->setCardholderName("Jax Teller")
    ->setSecurityCode("123");

//Adicionar dados do cliente
$transaction->customer("customer_210818263")
    ->setDocumentType(Customer::DOCUMENT_TYPE_CPF)
    ->setEmail("customer@email.com.br")
    ->setFirstName("Jax")
    ->setLastName("Teller")
    ->setName("Jax Teller")
    ->setPhoneNumber("5551999887766")
    ->setDocumentNumber("12345678912")
    ->billingAddress()
    ->setCity("São Paulo")
    ->setComplement("Sons of Anarchy")
    ->setCountry("Brasil")
    ->setDistrict("Centro")
    ->setNumber("1000")
    ->setPostalCode("90230060")
    ->setState("SP")
    ->setStreet("Av. Brasil");

//Adicionar dados de entrega
$transaction->shipping()
    ->setFirstName("Jax")
    ->setEmail("customer@email.com.br")
    ->setName("Jax Teller")
    ->setPhoneNumber("5551999887766")
    ->setShippingAmount(0)
    ->address()
    ->setCity("Porto Alegre")
    ->setComplement("Sons of Anarchy")
    ->setCountry("Brasil")
    ->setDistrict("São Geraldo")
    ->setNumber("1000")
    ->setPostalCode("90230060")
    ->setState("RS")
    ->setStreet("Av. Brasil");

$brandCryptToken = new BrandCryptToken(
    $brandToken->getNetworkTokenId(), 
    BrandCryptToken::TYPE_MERCHANT, 
    BrandCryptToken::TYPE_VISA,
    103.03,
    "customer_210818263",
    "customer@email.com.br",
    "VISA",
    $getnet
);

$transaction->tokenization()
    ->setType(Tokenization::TYPE_MASTER)
    ->setCryptogram($brandCryptToken->getCryptogram())
    ->setRequestorId($brandToken->getRequestId());

//Ou pode adicionar entrega com os mesmos dados do customer
//$transaction->addShippingByCustomer($transaction->getCustomer())->setShippingAmount(0);

//Adiciona o dispositivo
$transaction->device("device_id")->setIpAddress("127.0.0.1");

$response = $getnet->authorize($transaction);

print_r($response->getStatus()."\n");
```

--- 

### **Referências**
- Documentação da API: [[link para a documentação](https://developers.getnet.com.br/idempotency#tag/Tokenizacao-Bandeira)]