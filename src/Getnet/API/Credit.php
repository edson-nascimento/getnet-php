<?php

namespace Getnet\API;

use Getnet\API\Exception\GetnetException;

class Credit implements \JsonSerializable
{
    use TraitEntity;

    // Pagamento completo à vista
    public const TRANSACTION_TYPE_FULL = 'FULL';

    // Pagamento parcelado sem juros
    public const TRANSACTION_TYPE_INSTALL_NO_INTEREST = 'INSTALL_NO_INTEREST';

    // Pagamento parcelado com juros
    public const TRANSACTION_TYPE_INSTALL_WITH_INTEREST = 'INSTALL_WITH_INTEREST';

    public const COF_ONE_CLICK = 'ONE_CLICK';

    public const COF_ONE_CLICK_PAYMENT = 'ONE_CLICK_PAYMENT';

    public const COF_RECURRING = 'RECURRING';

    public const COF_RECURRING_PAYMENT = 'RECURRING_PAYMENT';

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

    // Tipo do COF (Credential On File).
    private $credentials_on_file_type;

    private $transaction_id;

    public function getAuthenticated()
    {
        return $this->authenticated;
    }

    public function setAuthenticated($authenticated)
    {
        $this->authenticated = $authenticated;

        return $this;
    }

    public function getDelayed()
    {
        return $this->delayed;
    }

    public function setDelayed($delayed)
    {
        $this->delayed = $delayed;

        return $this;
    }

    public function getDynamicMcc()
    {
        return $this->dynamic_mcc;
    }

    public function setDynamicMcc($dynamic_mcc)
    {
        $this->dynamic_mcc = (int) $dynamic_mcc;

        return $this;
    }

    public function getNumberInstallments()
    {
        return $this->number_installments;
    }

    public function setNumberInstallments($number_installments)
    {
        $this->number_installments = (int) $number_installments;

        return $this;
    }

    public function getPreAuthorization()
    {
        return $this->pre_authorization;
    }

    public function setPreAuthorization($pre_authorization)
    {
        $this->pre_authorization = $pre_authorization;

        return $this;
    }

    public function getSaveCardData()
    {
        return $this->save_card_data;
    }

    public function setSaveCardData($save_card_data)
    {
        $this->save_card_data = $save_card_data;

        return $this;
    }

    public function getSoftDescriptor()
    {
        return $this->soft_descriptor;
    }

    public function setSoftDescriptor($soft_descriptor)
    {
        $this->soft_descriptor = (string) $soft_descriptor;

        return $this;
    }

    public function getTransactionType()
    {
        return $this->transaction_type;
    }

    public function setTransactionType($transaction_type)
    {
        $this->transaction_type = (string) $transaction_type;

        return $this;
    }

    /**
     * @return Card
     */
    public function card(Token $token)
    {
        $card = new Card($token);

        $this->setCard($card);

        return $card;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }

    public function setCard(Card $card)
    {
        $this->card = $card;

        return $this;
    }

    public function getCardholderMobile()
    {
        return $this->cardholder_mobile;
    }

    public function setCardholderMobile($cardholder_mobile)
    {
        $this->cardholder_mobile = (string) $cardholder_mobile;

        return $this;
    }

    public function getCredentialsOnFileType()
    {
        return $this->credentials_on_file_type;
    }

    public function setCredentialsOnFileType($credentials_on_file_type)
    {
        if (!in_array($credentials_on_file_type, [
            static::COF_ONE_CLICK, static::COF_ONE_CLICK_PAYMENT, static::COF_RECURRING, static::COF_RECURRING_PAYMENT,
        ])) {
            throw new GetnetException('Escolha uma forma de recorrência válida');
        }

        $this->credentials_on_file_type = $credentials_on_file_type;

        return $this;
    }

    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    public function setTransactionId($transaction_id)
    {
        $this->transaction_id = (string) $transaction_id;

        return $this;
    }
}
