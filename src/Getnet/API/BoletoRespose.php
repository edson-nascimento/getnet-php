<?php

namespace Getnet\API;

// TODO rename to BoletoResponse in major version
class BoletoRespose extends BaseResponse
{
    public $boleto_id;

    public $bank;

    public $status_label;

    public $typeful_line;

    public $bar_code;

    public $issue_date;

    public $expiration_date;

    public $our_number;

    public $document_number;

    public $boleto_pdf;

    public $boleto_html;

    private $base_url;

    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;

        return $this;
    }

    public function generateLinks()
    {
        if ($this->getPaymentId()) {
            $this->boleto_pdf = $this->base_url . '/v1/payments/boleto/' . $this->getPaymentId() . '/pdf';
            $this->boleto_html = $this->base_url . '/v1/payments/boleto/' . $this->getPaymentId() . '/html';
        }
    }

    public function getBoletoPdf()
    {
        return $this->boleto_pdf;
    }

    public function getBoletoHtml()
    {
        return $this->boleto_html;
    }

    public function getDocumentNumber()
    {
        return $this->document_number;
    }

    /**
     * @return BoletoRespose
     */
    public function setDocumentNumber($document_number)
    {
        $this->document_number = $document_number;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return BaseResponse
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getBoletoId()
    {
        return $this->boleto_id;
    }

    /**
     * @return BoletoRespose
     */
    public function setBoletoId($boleto_id)
    {
        $this->boleto_id = $boleto_id;

        return $this;
    }

    public function getBank()
    {
        return $this->bank;
    }

    /**
     * @return BoletoRespose
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    public function getStatusLabel()
    {
        return $this->status_label;
    }

    /**
     * @return BoletoRespose
     */
    public function setStatusLabel($status_label)
    {
        $this->status_label = $status_label;

        return $this;
    }

    public function getTypefulLine()
    {
        return $this->typeful_line;
    }

    /**
     * @return BoletoRespose
     */
    public function setTypefulLine($typeful_line)
    {
        $this->typeful_line = $typeful_line;

        return $this;
    }

    public function getBarCode()
    {
        return $this->bar_code;
    }

    /**
     * @return BoletoRespose
     */
    public function setBarCode($bar_code)
    {
        $this->bar_code = $bar_code;

        return $this;
    }

    public function getIssueDate()
    {
        return $this->issue_date;
    }

    /**
     * @return BoletoRespose
     */
    public function setIssueDate($issue_date)
    {
        $this->issue_date = $issue_date;

        return $this;
    }

    public function getExpirationDate()
    {
        return $this->expiration_date;
    }

    /**
     * @return BoletoRespose
     */
    public function setExpirationDate($expiration_date)
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    public function getOurNumber()
    {
        return $this->our_number;
    }

    /**
     * @return BoletoRespose
     */
    public function setOurNumber($our_number)
    {
        $this->our_number = $our_number;

        return $this;
    }
}
