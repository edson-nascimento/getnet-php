<?php

namespace Getnet\API;

class AuthorizeResponse extends BaseResponse
{
    protected $delayed;

    protected $authorization_code;

    protected $authorized_at;

    protected $reason_code;

    protected $reason_message;

    protected $acquirer;

    protected $soft_descriptor;

    protected $brand;

    protected $terminal_nsu;

    protected $acquirer_transaction_id;

    protected $redirect_url;

    protected $issuer_payment_id;

    protected $payer_authentication_request;

    public function getRedirectUrl()
    {
        return $this->redirect_url;
    }

    public function setRedirectUrl($redirect_url)
    {
        $this->redirect_url = $redirect_url;

        return $this;
    }

    public function getIssuerPaymentId()
    {
        return $this->issuer_payment_id;
    }

    public function setIssuerPaymentId($issuer_payment_id)
    {
        $this->issuer_payment_id = $issuer_payment_id;

        return $this;
    }

    public function getPayerAuthenticationRequest()
    {
        return $this->payer_authentication_request;
    }

    public function setPayerAuthenticationRequest($payer_authentication_request)
    {
        $this->payer_authentication_request = $payer_authentication_request;

        return $this;
    }

    public function getDelayed()
    {
        return $this->delayed;
    }

    /**
     * @return AuthorizeResponse
     */
    public function setDelayed($delayed)
    {
        $this->delayed = $delayed;

        return $this;
    }

    public function getAuthorizationCode()
    {
        return $this->authorization_code;
    }

    /**
     * @return AuthorizeResponse
     */
    public function setAuthorizationCode($authorization_code)
    {
        $this->authorization_code = $authorization_code;

        return $this;
    }

    public function getAuthorizedAt()
    {
        return $this->authorized_at;
    }

    /**
     * @return AuthorizeResponse
     */
    public function setAuthorizedAt($authorized_at)
    {
        $this->authorized_at = $authorized_at;

        return $this;
    }

    public function getReasonCode()
    {
        return $this->reason_code;
    }

    /**
     * @return AuthorizeResponse
     */
    public function setReasonCode($reason_code)
    {
        $this->reason_code = $reason_code;

        return $this;
    }

    public function getReasonMessage()
    {
        return $this->reason_message;
    }

    /**
     * @return AuthorizeResponse
     */
    public function setReasonMessage($reason_message)
    {
        $this->reason_message = $reason_message;

        return $this;
    }

    public function getAcquirer()
    {
        return $this->acquirer;
    }

    /**
     * @return AuthorizeResponse
     */
    public function setAcquirer($acquirer)
    {
        $this->acquirer = $acquirer;

        return $this;
    }

    public function getSoftDescriptor()
    {
        return $this->soft_descriptor;
    }

    /**
     * @return AuthorizeResponse
     */
    public function setSoftDescriptor($soft_descriptor)
    {
        $this->soft_descriptor = $soft_descriptor;

        return $this;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @return AuthorizeResponse
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    public function getTerminalNsu()
    {
        return $this->terminal_nsu;
    }

    /**
     * @return AuthorizeResponse
     */
    public function setTerminalNsu($terminal_nsu)
    {
        $this->terminal_nsu = $terminal_nsu;

        return $this;
    }

    public function getAcquirerTransactionId()
    {
        return $this->acquirer_transaction_id;
    }

    /**
     * @return AuthorizeResponse
     */
    public function setAcquirerTransactionId($acquirer_transaction_id)
    {
        $this->acquirer_transaction_id = $acquirer_transaction_id;

        return $this;
    }
}
