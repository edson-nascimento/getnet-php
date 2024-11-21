<?php
namespace Getnet\API;

/**
 * Class BrandCryptToken
 *
 * @package Getnet\API
 */
class BrandCryptToken
{
    // Transação Iniciada pelo Cliente
    const TYPE_CONSUMER = "CIT";

    // Transação Iniciada pelo Comerciante - pagamento recorrente
    const TYPE_MERCHANT = "MIT";

    // Bandeira a qual se destina o token: Mastercard
    const TYPE_MASTER = "MC_DSRP_LONG";

    // Bandeira a qual se destina o token: Visa
    const TYPE_VISA = "VISA_TAVV";

    public $cryptogram;

    public $token_pan_card;

    public $token_expiration_month;

    public $token_expiration_year;

    public $token_status;

    protected $network_token_id;

    protected $transaction_type;

    protected $cryptogram_type;

    protected $amount;

    protected $customer_id;

    protected $email;

    protected $card_brand;

    /**
     * BrandCryptToken constructor.
     *
     * @param string $network_token_id
     * @param string $transaction_type
     * @param string $cryptogram_type
     * @param float $amount
     * @param string $customer_id
     * @param string $email
     * @param string $card_brand
     * @param Getnet $credencial
     */
    public function __construct(
        $network_token_id,
        $transaction_type,
        $cryptogram_type,
        $amount,
        $customer_id,
        $email,
        $card_brand,
        Getnet $credencial = null
    ) {
        $this->setNetworkTokenId($network_token_id)
            ->setTransactionType($transaction_type)
            ->setCryptogramType($cryptogram_type)
            ->setAmount($amount)
            ->setCustomerId($customer_id)
            ->setEmail($email)
            ->setCardBrand($card_brand);

        if ($credencial) {
            $this->generateCryptogram($credencial);
        }
    }

    /**
     *
     * @return mixed
     */
    public function getNetworkTokenId()
    {
        return $this->network_token_id;
    }

    /**
     *
     * @param mixed $network_token_id
     * @return BrandCryptToken
     */
    public function setNetworkTokenId($network_token_id)
    {
        $this->network_token_id = (string) $network_token_id;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->transaction_type;
    }

    /**
     *
     * @param mixed $transaction_type
     * @return BrandCryptToken
     */
    public function setTransactionType($transaction_type)
    {
        $this->transaction_type = (string) $transaction_type;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getCryptogramType()
    {
        return $this->cryptogram_type;
    }

    /**
     *
     * @param mixed $cryptogram_type
     * @return BrandCryptToken
     */
    public function setCryptogramType($cryptogram_type)
    {
        $this->cryptogram_type = (string) $cryptogram_type;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     *
     * @param mixed $amount
     * @return BrandCryptToken
     */
    public function setAmount($amount)
    {
        $this->amount = (float) $amount;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     *
     * @param mixed $customer_id
     * @return BrandCryptToken
     */
    public function setCustomerId($customer_id)
    {
        $this->customer_id = (string) $customer_id;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @param mixed $email
     * @return BrandCryptToken
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getCardBrand()
    {
        return $this->card_brand;
    }

    /**
     *
     * @param mixed $card_brand
     * @return BrandCryptToken
     */
    public function setCardBrand($card_brand)
    {
        $this->card_brand = (string) $card_brand;
        return $this;
    }

    public function getCryptogram()
    {
        return $this->cryptogram;
    }

    /**
     *
     * @return mixed
     */
    public function getTokenPanCard()
    {
        return $this->token_pan_card;
    }

    /**
     *
     * @return mixed
     */
    public function getTokenExpirationMonth()
    {
        return $this->token_expiration_month;
    }

    /**
     *
     * @return mixed
     */
    public function getTokenExpirationYear()
    {
        return $this->token_expiration_year;
    }

    /**
     *
     * @return mixed
     */
    public function getTokenStatus()
    {
        return $this->token_status;
    }

    /**
     * Generate cryptogram by sending the request to the API
     *
     * @param Getnet $credencial
     * @return BrandCryptToken
     */
    public function generateCryptogram(Getnet $credencial)
    {
        $data = array(
            "network_token_id" => $this->network_token_id,
            "transaction_type" => $this->transaction_type,
            "cryptogram_type" => $this->cryptogram_type,
            "amount" => $this->amount,
            "customer_id" => $this->customer_id,
            "email" => $this->email,
            "card_brand" => $this->card_brand,
        );

        $request = new Request($credencial);
        $response = $request->post($credencial, "/v1/tokenization/crypt", json_encode($data));

        $this->cryptogram = $response["cryptogram"];
        $this->token_pan_card = $response["token_pan_card"];
        $this->token_expiration_month = $response["token_expiration_month"];
        $this->token_expiration_year = $response["token_expiration_year"];
        $this->token_status = $response["token_status"];

        return $this;
    }
}
