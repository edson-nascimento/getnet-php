<?php

namespace Tests;

use Getnet\API\Boleto;
use Getnet\API\BoletoRespose;
use Getnet\API\Transaction;
use PHPUnit\Framework\Attributes\Group;

final class BoletoTest extends TestBase
{
    #[Group('e2e')]
    public function testBoletoCreate(): BoletoRespose
    {
        $transaction = $this->generateMockTransaction(false);
        $transaction->setAmount(1096.88);

        $transaction->boleto('000001946598')
            ->setDocumentNumber('170500000019763')
            ->setExpirationDate(date('d/m/Y', strtotime('+2 days')))
            ->setProvider(Boleto::PROVIDER_SANTANDER)
            ->setInstructions('Não receber após o vencimento');

        $response = $this->getnetService()->boleto($transaction);

        if (!($response instanceof BoletoRespose)) {
            throw new \Exception($response->getResponseJSON());
        }

        $this->assertSame(Transaction::STATUS_PENDING, $response->getStatus(), $response->getResponseJSON());
        $this->assertSame($transaction->getOrder()->getOrderId(), $response->getOrderId());
        $this->assertNotEmpty($response->getPaymentId());
        $this->assertNotEmpty($response->getBoletoId());
        $this->assertNotEmpty($response->getBarCode());
        $this->assertNotEmpty(filter_var($response->getBoletoHtml(), FILTER_VALIDATE_URL));
        $this->assertNotEmpty(filter_var($response->getBoletoPdf(), FILTER_VALIDATE_URL));

        return $response;
    }
}
