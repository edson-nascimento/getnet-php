<?php

namespace Getnet\API;

class Boleto implements \JsonSerializable
{
    use TraitEntity;

    public const PROVIDER_SANTANDER = 'santander';

    private $our_number;

    private $document_number;

    private $expiration_date;

    private $instructions;

    private $provider;

    /**
     * @param string|null $our_number
     */
    public function __construct($our_number = null)
    {
        if ($our_number) {
            $this->our_number = $our_number;
        }
    }

    public function getOurNumber()
    {
        return $this->our_number;
    }

    /**
     * @return Boleto
     */
    public function setOurNumber($our_number)
    {
        $this->our_number = (string) $our_number;

        return $this;
    }

    public function getDocumentNumber()
    {
        return $this->document_number;
    }

    /**
     * @return Boleto
     */
    public function setDocumentNumber($document_number)
    {
        $this->document_number = (string) $document_number;

        return $this;
    }

    public function getExpirationDate()
    {
        return $this->expiration_date;
    }

    /**
     * @return Boleto
     */
    public function setExpirationDate($expiration_date)
    {
        $this->expiration_date = (string) $expiration_date;

        return $this;
    }

    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @return Boleto
     */
    public function setInstructions($instructions)
    {
        $this->instructions = (string) $instructions;

        return $this;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return Boleto
     */
    public function setProvider($provider)
    {
        $this->provider = (string) $provider;

        return $this;
    }
}
