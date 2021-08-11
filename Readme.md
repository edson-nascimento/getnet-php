### GETNET SDK PHP - API v1
E-commerce

Todos os passos e processos referentes à integração com o sistema de captura e autorização de transações financeiras da Getnet via as funcionalidades da API.

 Documentação oficial
* https://developers.getnet.com.br/api

#### Composer

add composer.json
```
"smartdata-co/getnet-php": "^1.1"
```
ou execute
```
$ composer require smartdata-co/getnet-php
```
#### Exemplo Autorização com cartão de crédito MasterCard R$27,50 em 2x 

```php
use Getnet\API\Getnet;
use Getnet\API\Transaction;
use Getnet\API\Environment;
use Getnet\API\Token;
use Getnet\API\Credit;
use Getnet\API\Customer;
use Getnet\API\Card;
use Getnet\API\Order;
use Getnet\API\Boleto;

include 'vendor/autoload.php';

$client_id      = "3a666a8c-6d97-4eb0-a62c-77e3758c3425";
$client_secret  = "f52a2358-70e6-4baa-b77f-9f0eeb7c8706";
$seller_id      = "c695b415-6f2e-4475-a221-3c005258a450";
$environment    = Environment::sandbox();

//Opicional, passar chave se você quiser guardar o token do auth na sessão para não precisar buscar a cada trasação, só quando expira
$keySession = null;

//Autenticação da API
$getnet = new Getnet($client_id, $client_secret, $environment, $keySession);

// Inicia uma transação
$transaction = new Transaction();

// Dados do pedido - Transação
$transaction->setSellerId($seller_id);
$transaction->setCurrency("BRL");
$transaction->setAmount(27.50);

// Detalhes do Pedido
$transaction->order("123456")
->setProductType(Order::PRODUCT_TYPE_SERVICE)
->setSalesTax(0);

// Gera token do cartão - Obrigatório
$tokenCard = new Token("5155901222280001", "customer_210818263", $getnet);

// Dados do método de pagamento do comprador
$transaction->credit()
            ->setAuthenticated(false)
            ->setDynamicMcc("1799")
            ->setSoftDescriptor("LOJA*TESTE*COMPRA-123")
            ->setDelayed(false)
            ->setPreAuthorization(false)
            ->setNumberInstallments(2)
            ->setSaveCardData(false)
            ->setTransactionType(Credit::TRANSACTION_TYPE_INSTALL_NO_INTEREST)
            ->card($tokenCard)
                ->setBrand(Card::BRAND_MASTERCARD)
                ->setExpirationMonth("12")
                ->setExpirationYear("20")
                ->setCardholderName("Jax Teller")
                ->setSecurityCode("123");

// Dados pessoais do comprador
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

// Dados de entrega do pedido
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

//Ou pode adicionar entrega com os mesmos dados do customer
//$transaction->addShippingByCustomer($transaction->getCustomer())->setShippingAmount(0);

// FingerPrint - Antifraude
$transaction->device("device_id")->setIpAddress("127.0.0.1");

// Processa a Transação
$response = $getnet->authorize($transaction);

// Resultado da transação - Consultar tabela abaixo
$response->getStatus();
```

#### CONFIRMA PAGAMENTO (CAPTURA)
```php
// Autenticação da API
$getnet = new Getnet($client_id, $client_secret, $environment, $keySession);

// Processa a confirmação da autorização
$capture = $getnet->authorizeConfirm("PAYMENT_ID");

// Resultado da transação - Consultar tabela abaixo
$capture->getStatus();
```

#### CANCELA PAGAMENTO (CRÉDITO e DÉBITO)
```php
// Autenticação da API
$getnet = new Getnet($client_id, $client_secret, $environment, $keySession);

$cancel = $getnet->authorizeCancel("[PAYMENT_ID]", [AMOUNT]);

// Resultado da transação - Consultar tabela abaixo
$cancel->getStatus();
```

#### CARTÃO DE DÉBITO
```php
// Autenticação da API
$getnet = new Getnet($client_id, $client_secret, $environment, $keySession);

// URL de callback
$URL_NOTIFY = "http://localhost/url-notify";

//Adicionar dados do Pagamento no lugar do credit ou resto é igual ao cartão de crédito
$transaction->debit()
            ->setCardholderMobile("5551999887766")
            ->setDynamicMcc("1799")
            ->setSoftDescriptor("LOJA*TESTE*COMPRA-123")
            ->card($tokenCard)
                ->setBrand(Card::BRAND_MASTERCARD)
                ->setExpirationMonth("12")
                ->setExpirationYear("20")
                ->setCardholderName("Jax Teller")
                ->setSecurityCode("123");

$response = $getnet->authorize($transaction);
```

*Depois de autorizar é preciso redirecionar o cliente para o redirect_url passando uma url de callback

```html
<form action="<?php echo $response->getRedirectUrl();?>" method="post" target="_blank">
    <input type="hidden" name="MD"  value="<?php echo $response->getIssuerPaymentId();?>" />
    <input type="hidden" name="PaReq"  value="<?php echo $response->getPayerAuthenticationRequest();?>" />
    <input type="hidden" name="TermUrl"  value="<?php echo $URL_NOTIFY;?>" />
    
    <input type="submit" value="Authentication Card" />
</form>
```

*Depois do cliente finalizar o pagamento e você receber o callback

```php
//CONFIRMAR O PAGAMENTO COM payer_authentication_response recibo na URL de Noficação
$response = $getnet->authorizeConfirmDebit($response->getPaymentId(), $payer_authentication_response);

// Resultado da transação - Consultar tabela abaixo
$response->getStatus();
```

#### BOLETO BANCÁRIO (SANTANDER)

```php
//Autenticação da API
$getnet = new Getnet($client_id, $client_secret, $environment, $keySession);

//Cria a transação
$transaction = new Transaction();
$transaction->setSellerId($seller_id);
$transaction->setCurrency("BRL");
$transaction->setAmount(75.50);

//Adicionar dados do Pedido
$transaction->order("123456")
->setProductType(Order::PRODUCT_TYPE_SERVICE)
->setSalesTax(0);

$transaction->boleto("000001946598")
            ->setDocumentNumber("170500000019763")
            ->setExpirationDate("21/11/2018")
            ->setProvider(Boleto::PROVIDER_SANTANDER)
            ->setInstructions("Não receber após o vencimento");

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

$response = $getnet->boleto($transaction);

// Resultado da transação - Consultar tabela abaixo
$response->getStatus();
```

### Possíveis status de resposta de uma transação
|Status|Descrição|
| ------- | --------- |
|PENDING|Registrada ou Aguardando ação|
|CANCELED|Desfeita ou Cancelada|
|APPROVED|Aprovada|
|DENIED|Negada|
|AUTHORIZED|Autorizada pelo emissor|
|CONFIRMED|Confirmada ou Capturada|

### Cartões para testes

|  N. Cartão |  Resultado esperado |
| ------------ | ------------ |
|  5155901222280001 (Master)	  | Transação Autorizada  |
| 5155901222270002   (Master)|  Transação Não Autorizada |
|  5155901222260003 (Master) |  Transação Não Autorizada |
| 5155901222250004 (Master) |Transação Não Autorizada|
| 4012001037141112 (Visa) |Transação Autorizada|


### Ambientes disponíveis
|Paramentro|Detalhe|
| ------- | --------- |
|SANDBOX|Sandbox - para desenvolvedores |
|HOMOLOG|Homologação - para lojistas e devs |
|PRODUCTION|Produção - somente lojistas |

### Meios de Pagamento
|Modalidade|Descrição|
| ------- | --------- |
|CREDIT|Pagamento com cartão de crédito|
|DEBIT|Pagamento com cartão de débito|
|BOLETO|Gera boleto|


### Métodos de Pagamento
|Método|Descrição|
| ------- | --------- |
|authorize|Autoriza uma transação com Pre-Auth ou não|
|authorizeConfirm|Confirma uma autorização de crédito|
|authorizeConfirmDebit|Confirma uma autorização de débito|
|authorizeCancel|Cancela a transação|
|boleto|Gera boleto|


