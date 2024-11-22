<?php
namespace Getnet\API;

/**
 * Class BrandToken
 *
 * @package Getnet\API
 */
class BrandToken
{
    public $request_id;

    public $network_token_id;

    public $token_status;

    protected $customer_id;

    protected $email;

    protected $card_brand;

    protected $card_pan_source;

    protected $card_pan;

    protected $security_code;

    protected $expiration_month;

    protected $expiration_year;

    /**
     * BrandToken constructor.
     *
     * @param string $customer_id
     * @param string $email
     * @param string $card_brand
     * @param string $card_pan_source
     * @param string $card_pan
     * @param string $security_code
     * @param string $expiration_month
     * @param string $expiration_year
     * @param Getnet $credencial
     */
    public function __construct(
        $customer_id,
        $email,
        $card_brand,
        $card_pan_source,
        $card_pan,
        $security_code,
        $expiration_month,
        $expiration_year,
        Getnet $credencial = null
    ) {
        $this->setCustomerId($customer_id)
            ->setEmail($email)
            ->setCardBrand($card_brand)
            ->setCardPanSource($card_pan_source)
            ->setCardPan($card_pan)
            ->setSecurityCode($security_code)
            ->setExpirationMonth($expiration_month)
            ->setExpirationYear($expiration_year);

        if ($credencial) {
            $this->generateToken($credencial);
        }
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
     * @return BrandToken
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
     * @return BrandToken
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
     * @return BrandToken
     */
    public function setCardBrand($card_brand)
    {
        $this->card_brand = (string) $card_brand;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getCardPanSource()
    {
        return $this->card_pan_source;
    }


    /**
     *
     * @param mixed $card_pan_source
     * @return BrandToken
     */
    public function setCardPanSource($card_pan_source)
    {
        $this->card_pan_source = (string) $card_pan_source;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getCardPan()
    {
        return $this->card_pan;
    }

    /**
     *
     * @param mixed $card_pan
     * @return BrandToken
     */
    public function setCardPan($card_pan)
    {
        $this->card_pan = (string) $card_pan;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getSecurityCode()
    {
        return $this->security_code;
    }

    /**
     *
     * @param mixed $security_code
     * @return BrandToken
     */
    public function setSecurityCode($security_code)
    {
        $this->security_code = (string) $security_code;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getExpirationMonth()
    {
        return $this->expiration_month;
    }

    /**
     *
     * @param mixed $expiration_month
     * @return BrandToken
     */
    public function setExpirationMonth($expiration_month)
    {
        $this->expiration_month = (string) $expiration_month;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getExpirationYear()
    {
        return $this->expiration_year;
    }

    /**
     *
     * @param mixed $expiration_year
     * @return BrandToken
     */
    public function setExpirationYear($expiration_year)
    {
        $this->expiration_year = (string) $expiration_year;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->request_id;
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
     * @return mixed
     */
    public function getTokenStatus()
    {
        return $this->token_status;
    }

    /**
     *
     * @param Getnet $credencial
     * @return BrandToken
     */
    public function generateToken(Getnet $credencial)
    {
        $data = array(
            "customer_id" => $this->customer_id,
            "email" => $this->email,
            "card_brand" => $this->card_brand,
            "card_pan_source" => $this->card_pan_source,
            "card_pan" => $this->card_pan,
            "security_code" => $this->security_code,
            "expiration_month" => $this->expiration_month,
            "expiration_year" => $this->expiration_year
        );

        $request = new Request($credencial);
        $response = $request->post($credencial, "/v1/tokenization/token", json_encode($data));
        $this->request_id = $response["request_id"];
        $this->network_token_id = $response["network_token_id"];
        $this->token_status = $response["token_status"];

        return $this;
    }
}
