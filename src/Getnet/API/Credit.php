<?php

namespace Getnet\API;

/**
 * Class Credit
 *
 * @package Getnet\API
 */
class Credit implements \JsonSerializable
{
    use TraitEntity;

    // Pagamento completo à vista
    const TRANSACTION_TYPE_FULL = "FULL";

    // Pagamento parcelado sem juros
    const TRANSACTION_TYPE_INSTALL_NO_INTEREST = "INSTALL_NO_INTEREST";

    // Pagamento parcelado com juros
    const TRANSACTION_TYPE_INSTALL_WITH_INTEREST = "INSTALL_WITH_INTEREST";

    private $authenticated;

    private $delayed;

    private $dynamic_mcc;

    private $number_installments;

    private $pre_authorization = false;

    private $save_card_data;

    private $soft_descriptor;

    private $transaction_type;

    private $card;

    private $cardholder_mobile;

    private $credentials_on_file_type;

    private $transaction_id;

    /**
     *
     * @return mixed
     */
    public function getAuthenticated()
    {
        return $this->authenticated;
    }

    /**
     *
     * @param mixed $authenticated
     */
    public function setAuthenticated($authenticated)
    {
        $this->authenticated = $authenticated;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getDelayed()
    {
        return $this->delayed;
    }

    /**
     *
     * @param mixed $delayed
     */
    public function setDelayed($delayed)
    {
        $this->delayed = $delayed;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getDynamicMcc()
    {
        return $this->dynamic_mcc;
    }

    /**
     *
     * @param mixed $dynamic_mcc
     */
    public function setDynamicMcc($dynamic_mcc)
    {
        $this->dynamic_mcc = (int) $dynamic_mcc;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getNumberInstallments()
    {
        return $this->number_installments;
    }

    /**
     *
     * @param mixed $number_installments
     */
    public function setNumberInstallments($number_installments)
    {
        $this->number_installments = (int) $number_installments;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getPreAuthorization()
    {
        return $this->pre_authorization;
    }

    /**
     *
     * @param mixed $pre_authorization
     */
    public function setPreAuthorization($pre_authorization)
    {
        $this->pre_authorization = $pre_authorization;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getSaveCardData()
    {
        return $this->save_card_data;
    }

    /**
     *
     * @param mixed $save_card_data
     */
    public function setSaveCardData($save_card_data)
    {
        $this->save_card_data = $save_card_data;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getSoftDescriptor()
    {
        return $this->soft_descriptor;
    }

    /**
     *
     * @param mixed $soft_descriptor
     */
    public function setSoftDescriptor($soft_descriptor)
    {
        $this->soft_descriptor = (string) $soft_descriptor;

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
     */
    public function setTransactionType($transaction_type)
    {
        $this->transaction_type = (string) $transaction_type;

        return $this;
    }

    /**
     *
     * @param Token $token
     * @return Card
     */
    public function card(Token $token)
    {
        $card = new Card($token);

        $this->setCard($card);

        return $card;
    }

    /**
     *
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     *
     * @param Card $card
     */
    public function setCard(Card $card)
    {
        $this->card = $card;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getCardholderMobile()
    {
        return $this->cardholder_mobile;
    }

    /**
     *
     * @param mixed $cardholder_mobile
     */
    public function setCardholderMobile($cardholder_mobile)
    {
        $this->cardholder_mobile = (string) $cardholder_mobile;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getCredentialsOnFileType()
    {
        return $this->credentials_on_file_type;
    }

    /**
     *
     * @param mixed $credentials_on_file_type
     */
    public function setCredentialsOnFileType($credentials_on_file_type)
    {
        $this->credentials_on_file_type = $credentials_on_file_type;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     *
     * @param mixed $transaction_id
     */
    public function setTransactionId($transaction_id)
    {
        $this->transaction_id = $transaction_id;

        return $this;
    }
}
