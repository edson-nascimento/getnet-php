<?php
namespace Getnet\API;

use Getnet\API\Exception\GetnetException;

/**
 * Class Getnet
 *
 * @package Getnet\API
 */
class Getnet
{

    private $client_id;

    private $client_secret;

    private $seller_id;

    private $environment;

    private $authorizationToken;

    private $keySession;

    // TODO add monolog
    private $debug = false;

    /**
     *
     * @param string $client_id
     * @param string $client_secret
     * @param Environment|null $environment
     * @return Getnet
     */
    public function __construct($client_id, $client_secret, Environment $environment = null, $keySession = null)
    {
        if (! $environment) {
            $environment = Environment::production();
        }

        $this->setClientId($client_id);
        $this->setClientSecret($client_secret);
        $this->setEnvironment($environment);
        $this->setKeySession($keySession);

        // TODO refactor auth in constructor Request
        new Request($this);
    }

    /**
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     *
     * @param string $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = (string) $client_id;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->client_secret;
    }

    /**
     *
     * @param mixed $client_secret
     */
    public function setClientSecret($client_secret)
    {
        $this->client_secret = (string) $client_secret;

        return $this;
    }

    public function getSellerId()
    {
        return $this->seller_id;
    }

    public function setSellerId($seller_id)
    {
        $this->seller_id = (string) $seller_id;

        return $this;
    }

    /**
     *
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     *
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getAuthorizationToken()
    {
        return $this->authorizationToken;
    }

    /**
     *
     * @param mixed $authorizationToken
     */
    public function setAuthorizationToken($authorizationToken)
    {
        $this->authorizationToken = (string) $authorizationToken;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getKeySession()
    {
        return $this->keySession;
    }

    /**
     *
     * @param mixed $keySession
     */
    public function setKeySession($keySession)
    {
        $this->keySession = (string) $keySession;
    }

    /**
     *
     * @return bool|null
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     *
     * @param bool|null $debug
     */
    public function setDebug($debug = false)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     *
     * @param Transaction $transaction
     * @return BaseResponse|AuthorizeResponse
     */
    public function authorize(Transaction $transaction)
    {
        try {
            if ($this->debug) {
                print $transaction->toJSON();
            }

            $request = new Request($this);

            $response = null;
            if ($transaction->getCredit()) {
                $response = $request->post($this, "/v1/payments/credit", $transaction->toJSON());
            } elseif ($transaction->getDebit()) {
                $response = $request->post($this, "/v1/payments/debit", $transaction->toJSON());
            } else {
                throw new GetnetException("Error select credit or debit");
            }

            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     *
     * @param mixed $payment_id
     * @return BaseResponse|AuthorizeResponse
     */
    public function authorizeConfirm($payment_id, $amount)
    {
        $bodyParams = array(
            'amount' => $amount
        );

        try {
            if ($this->debug) {
                print json_encode($bodyParams);
            }

            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/credit/" . $payment_id . "/confirm", $bodyParams);

            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     *
     * @param mixed $payment_id
     * @param mixed $payer_authentication_response
     * @return BaseResponse|AuthorizeResponse
     */
    public function authorizeConfirmDebit($payment_id, $payer_authentication_response)
    {
        $bodyParams = array(
            "payer_authentication_response" => $payer_authentication_response
        );

        try {
            if ($this->debug) {
                print json_encode($bodyParams);
            }

            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/debit/" . $payment_id . "/authenticated/finalize", json_encode($bodyParams));

            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     * Estorna ou desfaz transações feitas no mesmo dia (D0).
     *
     * @param string $payment_id
     * @param int|string $amount_val
     * @return AuthorizeResponse|BaseResponse
     */
    public function authorizeCancel($payment_id, $amount_val)
    {
        $bodyParams = array(
            "amount" => $amount_val
        );

        try {
            if ($this->debug) {
                print json_encode($bodyParams);
            }

            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/credit/" . $payment_id . "/cancel", json_encode($bodyParams));

            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     * Solicita o cancelamento de transações que foram realizadas há mais de 1 dia (D+n).
     *
     * @param mixed $payment_id
     * @param mixed $cancel_amount
     * @param mixed $cancel_custom_key
     * @return AuthorizeResponse|BaseResponse
     */
    public function cancelTransaction($payment_id, $cancel_amount, $cancel_custom_key)
    {
        $bodyParams = array(
            "payment_id" => $payment_id,
            "cancel_amount" => $cancel_amount,
            "cancel_custom_key" => $cancel_custom_key
        );

        try {
            if ($this->debug) {
                print json_encode($bodyParams);
            }

            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/cancel/request", json_encode($bodyParams));
            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);

            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     * Retorna os dados da solicitação de cancelamento pela chave gerada pelo cliente ou pelo identificador (D+n).
     *
     * @return AuthorizeResponse|BaseResponse
     */
    public function getCancellationRequest(string $cancelRequestId)
    {
        try {
            $request = new Request($this);
            $response = $request->get($this, "/v1/payments/cancel/request/" . $cancelRequestId);
            $authresponse = new AuthorizeResponse();
            $authresponse->mapperJson($response);
            
            return $authresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     *
     * @param Transaction $transaction
     * @return BaseResponse|BoletoRespose
     */
    public function boleto(Transaction $transaction)
    {
        try {
            if ($this->debug) {
                print $transaction->toJSON();
            }

            $request = new Request($this);
            $response = $request->post($this, "/v1/payments/boleto", $transaction->toJSON());

            $boletoresponse = new BoletoRespose();
            $boletoresponse->mapperJson($response);
            $boletoresponse->setBaseUrl($request->getBaseUrl());
            $boletoresponse->generateLinks();

            return $boletoresponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }

    /**
     * Payment confirmation is sent via notifications
     *
     * @param PixTransaction $pix
     * @return BaseResponse|PixResponse
     * @link https://developers.getnet.com.br/api#tag/Notificacoes-1.0
     */
    public function pix(PixTransaction $pix)
    {
        try {
            if ($this->debug) {
                print $pix->toJSON();
            }

            $request = new Request($this);

            if ($pix->getExpirationTime() > 0 && $pix->getExpirationTime() <= 1800) {
                $request->addCustomHeader("x-qrcode-expiration-time: {$pix->getExpirationTime()}");
            }

            $response = $request->post($this, "/v1/payments/qrcode/pix", $pix->toJSON());

            $pixResponse = new PixResponse();
            // Add fields do not return in response
            $pixResponse->mapperJson($pix->toArray());
            // Add response fields
            $pixResponse->mapperJson($response);

            return $pixResponse;
        } catch (\Exception $e) {
            return $this->generateErrorResponse($e);
        }
    }
    
     /** @param string[] $headers */
    public function customRequest(string $method, string $url_path, $body = null, array $headers = [])
    {
        $request = new Request($this);
        $request->setCustomHeaders($headers);
        
        return $request->custom($this, $method, $url_path, $body);
    }
    
    /**
     * 
     * @param \Exception $e
     * @return \Getnet\API\BaseResponse
     */
    private function generateErrorResponse($e)
    {
        $error = new BaseResponse();
        $error->mapperJson(json_decode($e->getMessage(), true));
        
        if (empty($error->getStatus())) {
            $error->setStatus(Transaction::STATUS_ERROR);
        }
        
        return $error;
    }
}