<?php

namespace Tests;

use Getnet\API\PixTransaction;
use Getnet\API\Transaction;

final class TransactionTest extends TestBase
{
    public function testTransactionAmount(): void
    {
        $transaction = new Transaction();
        $this->assertNull($transaction->getAmount());

        $transaction->setAmount(76.89);
        $this->assertSame(7689, $transaction->getAmount());
        $transaction->setAmount('76.89');
        $this->assertSame(7689, $transaction->getAmount());

        $transaction->setAmount(7628.89);
        $this->assertSame(762889, $transaction->getAmount());
        $transaction->setAmount('7628.89');
        $this->assertSame(762889, $transaction->getAmount());

        $transaction->setAmount(10000);
        $this->assertSame(1000000, $transaction->getAmount());
        $transaction->setAmount('10000');
        $this->assertSame(1000000, $transaction->getAmount());

        $transaction->setAmount(142.2);
        $this->assertSame(14220, $transaction->getAmount());
        $transaction->setAmount('142.2');
        $this->assertSame(14220, $transaction->getAmount());
    }

    public function testPixTransactionAmount(): void
    {
        $pixTransaction = new PixTransaction();
        $this->assertNull($pixTransaction->getAmount());

        $pixTransaction->setAmount(76.89);
        $this->assertSame(7689, $pixTransaction->getAmount());
        $pixTransaction->setAmount('76.89');
        $this->assertSame(7689, $pixTransaction->getAmount());

        $pixTransaction->setAmount(7628.89);
        $this->assertSame(762889, $pixTransaction->getAmount());
        $pixTransaction->setAmount('7628.89');
        $this->assertSame(762889, $pixTransaction->getAmount());

        $pixTransaction->setAmount(10000);
        $this->assertSame(1000000, $pixTransaction->getAmount());
        $pixTransaction->setAmount('10000');
        $this->assertSame(1000000, $pixTransaction->getAmount());

        $pixTransaction->setAmount(142.2);
        $this->assertSame(14220, $pixTransaction->getAmount());
        $pixTransaction->setAmount('142.2');
        $this->assertSame(14220, $pixTransaction->getAmount());
    }
}
