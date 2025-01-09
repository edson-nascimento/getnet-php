<?php
use Getnet\API\Card;
use Getnet\API\Customer;
use Getnet\API\Getnet;
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
$transaction->setAmount(33.33);

// Adicionar dados do Pedido
$transaction->order('123456')
            ->setProductType(Order::PRODUCT_TYPE_SERVICE)
            ->setSalesTax(0);

// Criar token Cartão
$tokenCard = new Token('5155901222280001', 'customer_210818263', $getnet);

// Adicionar dados do Pagamento
$transaction->debit()
            ->setCardholderMobile('5551999887766')
            ->setDynamicMcc('1799')
            ->setSoftDescriptor('LOJA*TESTE*COMPRA-123')
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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response = $getnet->authorize($transaction);
    print_r($response->getStatus() . "\n");

    if (!($response instanceof \Getnet\API\AuthorizeResponse)) {
        return;
    }

    // Pega a url atual como retorno
    $URL_NOTIFY = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . "?payment_id={$response->getPaymentId()}";
    ?>
    <form action="<?php echo $response->getRedirectUrl(); ?>" method="post" target="_blank">
        <input type="hidden" name="MD"  value="<?php echo $response->getIssuerPaymentId(); ?>" />
        <input type="hidden" name="PaReq"  value="<?php echo $response->getPayerAuthenticationRequest(); ?>" />
        <input type="hidden" name="TermUrl"  value="<?php echo $URL_NOTIFY; ?>" />
        
        <input type="submit" value="Authentication Card" />
    </form>

<?php
// https://developers.getnet.com.br/simulator demora um pouco para processar e retornar o POST
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_id = $_GET['payment_id'];
    $paRes = $_POST['PaRes'];

    // CONFIRMAR O PAGAMENTO COM payer_authentication_response recibo na URL de Noficação
    $response = $getnet->authorizeConfirmDebit($payment_id, $paRes);
    print_r($response->getStatus() . "\n");

    // ## CANCELA PAGAMENTO (CANCEL)
    $cancel = $getnet->cancelTransaction($payment_id, $response->getAmount(), uniqid());

    print_r($cancel->getStatus() . "\n");
}
