<?php

namespace Getnet\API;

class PixResponse extends BaseResponse
{
    protected $transaction_id;

    protected $qr_code;

    protected $creation_date_qrcode;

    protected $expiration_date_qrcode;

    protected $psp_code;

    // gets and sets
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    public function setTransactionId($transaction_id)
    {
        $this->transaction_id = $transaction_id;

        return $this;
    }

    public function getQrCode()
    {
        return $this->qr_code;
    }

    public function setQrCode($qr_code)
    {
        $this->qr_code = $qr_code;

        return $this;
    }

    public function getCreationDateQrcode()
    {
        return $this->creation_date_qrcode;
    }

    public function setCreationDateQrcode($creation_date_qrcode)
    {
        $this->creation_date_qrcode = $creation_date_qrcode;

        return $this;
    }

    public function getExpirationDateQrcode()
    {
        return $this->expiration_date_qrcode;
    }

    public function setExpirationDateQrcode($expiration_date_qrcode)
    {
        $this->expiration_date_qrcode = $expiration_date_qrcode;

        return $this;
    }

    public function getPspCode()
    {
        return $this->psp_code;
    }

    public function setPspCode($psp_code)
    {
        $this->psp_code = $psp_code;

        return $this;
    }
}
