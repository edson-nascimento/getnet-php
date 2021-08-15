<?php
namespace Getnet\API;

/**
 * Class Getnet
 *
 * @package Getnet\API
 */
class Getnet {

    private $client_id;

    private $client_secret;

    private $environment;

    private $authorizationToken;

    private $keySession;
    

    /**
     * 
     * @param mixed $client_id
     * @param mixed $client_secret
     * @param mixed $env
     * @return Getnet
     */
    public function __construct($client_id, $client_secret, Environment $environment = null, $keySession = null) {
        
        if (!$environment) {
            $environment = Environment::production();
        }
        
        $this->setClientId($client_id);
        $this->setClientSecret($client_secret);
        $this->setEnvironment($environment);
        $this->setKeySession($keySession);

        $request = new Request($this);

        return $request->auth($this);
    }
    
    /**
     * @return \Getnet\API\Request
     */
    public function getClientId() {
        return $this->client_id;
    }

    /**
     * @param \Getnet\API\Request $client_id
     */
    public function setClientId($client_id) {
        $this->client_id = (string)$client_id;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientSecret() {
        return $this->client_secret;
    }

    /**
     * @param mixed $client_secret
     */
    public function setClientSecret($client_secret) {
        $this->client_secret = (string)$client_secret;
        
        return $this;
    }

    /**
     * @return Environment
     */
    public function getEnvironment() {
        return $this->environment;
    }

    /**
     * @param string $environment
     */
    public function setEnvironment(Environment $environment) {
        $this->environment = $environment;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationToken() {
        return $this->authorizationToken;
    }

    /**
     * @param mixed $authorizationToken
     */
    public function setAuthorizationToken($authorizationToken) {
        $this->authorizationToken = (string)$authorizationToken;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getKeySession() {
        return $this->keySession;
    }
    
    /**
     * @param mixed $keySession
     */
    public function setKeySession($keySession) {
        $this->keySession = (string)$keySession;
    }

    /**
     * 
     * @param Transaction $transaction
     * @return BaseResponse|AuthorizeResponse
     */
    public function authorize(Transaction $transaction) {
        try {

            $request = new Request($this);

            if ($transaction->getCredit()) {
                $response = $request->post($this, "/v1/payments/credit", $transaction->toJSON());
            } elseif ($transaction->getDebit()) {
                $response = $request->post($this, "/v1/payments/debit", $transaction->toJSON());
            }else{
                throw new \Exception("Error select credit or debit");
            }
        } catch (\Exception $e) {

            $error = new BaseResponse();
            $error->mapperJson(json_decode($e->getMessage(), true));

            return $error;
        }

        $authresponse = new AuthorizeResponse();
        $authresponse->mapperJson($response);

        return $authresponse;
    }

    /**
     * 
     * @param mixed $payment_id
     * @return BaseResponse|AuthorizeResponse
     */
    public function authorizeConfirm($payment_id) {
        try {
            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/credit/".$payment_id."/confirm", "");
        } catch (\Exception $e) {

            $error = new BaseResponse();
            $error->mapperJson(json_decode($e->getMessage(), true));

            return $error;
        }
        
        $authresponse = new AuthorizeResponse();
        $authresponse->mapperJson($response);

        return $authresponse;
    }

    /**
     * 
     * @param mixed $payment_id
     * @param mixed $payer_authentication_response
     * @return BaseResponse|AuthorizeResponse
     */
    public function authorizeConfirmDebit($payment_id, $payer_authentication_response) {
        try {
            $payer_authentication_response = array("payer_authentication_response" => $payer_authentication_response);
            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/debit/".$payment_id."/authenticated/finalize", json_encode($payer_authentication_response));
        } catch (\Exception $e) {

            $error = new BaseResponse();
            $error->mapperJson(json_decode($e->getMessage(), true));

            return $error;
        }
        
        $authresponse = new AuthorizeResponse();
        $authresponse->mapperJson($response);

        return $authresponse;
    }

    /**
     * Estorna ou desfaz transações feitas no mesmo dia (D0).
     *
     * @param $payment_id
     * @param $amount_val
     * @return AuthorizeResponse|BaseResponse
     */
    public function authorizeCancel($payment_id, $amount_val) {
        $amount = array("amount" => $amount_val);

        try {
            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/credit/".$payment_id."/cancel", json_encode($amount));
        } catch (\Exception $e) {

            $error = new BaseResponse();
            $error->mapperJson(json_decode($e->getMessage(), true));

            return $error;
        }
        
        $authresponse = new AuthorizeResponse();
        $authresponse->mapperJson($response);

        return $authresponse;
    }

    /**
     * Solicita o cancelamento de transações que foram realizadas há mais de 1 dia (D+n).
     * 
     * @param mixed $payment_id
     * @param mixed $cancel_amount
     * @param mixed $cancel_custom_key
     * @return AuthorizeResponse|BaseResponse
     */
    public function cancelTransaction($payment_id, $cancel_amount, $cancel_custom_key) {
        
        $params = array("payment_id"=>$payment_id, "cancel_amount"=>$cancel_amount, "cancel_custom_key"=>$cancel_custom_key);

        try {
            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/cancel/request", json_encode($params));
        } catch (\Exception $e) {

            $error = new BaseResponse();
            $error->mapperJson(json_decode($e->getMessage(), true));

            return $error;
        }
        
        $authresponse = new AuthorizeResponse();
        $authresponse->mapperJson($response);

        return $authresponse;
    }

    /**
     *
     * @param Transaction $transaction
     * @return BaseResponse|BoletoRespose
     */
    public function boleto(Transaction $transaction) {
        try {
            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/boleto", $transaction->toJSON());
            
            $boletoresponse = new BoletoRespose();
            $boletoresponse->mapperJson($response);
            $boletoresponse->setBaseUrl($request->getBaseUrl());
            $boletoresponse->generateLinks();
    
            return $boletoresponse;
        } catch (\Exception $e) {

            $error = new BaseResponse();
            $error->mapperJson(json_decode($e->getMessage(), true));

            return $error;
        }
    }

    /**
     *
     * @param Transaction $transaction
     * @return BaseResponse|PixResponse
     */
    public function pix(Transaction $transaction) {
        try {
            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/qrcode/pix", json_encode([
                "amount" => $transaction->getAmount(),
                "currency" => $transaction->getCurrency(),
                "order_id" => $transaction->getOrder()->getOrderId(),
                "customer_id" => $transaction->getCustomer()->getCustomerId(),
            ]));

            $pixresponse = new PixResponse();
            $pixresponse->mapperJson($response);
            // $pixresponse->setBaseUrl($request->getBaseUrl());
            // $pixresponse->generateImage();

        } catch (\Exception $e) {
            $error = new PixResponse();
            $error->mapperJson(json_decode($e->getMessage(), true));
            return $error;
        }
        
        
        return $pixresponse;
    }


}