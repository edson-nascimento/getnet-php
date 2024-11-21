<?php
namespace Getnet\API;

/**
 * Class Credit
 *
 * @package Getnet\API
 */
class Tokenization implements \JsonSerializable {
    use TraitEntity;

    // Bandeira a qual se destina o token: Mastercard
    const TYPE_MASTER = "UCAF";

    // Bandeira a qual se destina o token: Visa
    const TYPE_VISA = "TAVV";

    private $type;

    private $cryptogram;

    private $eci;

    private $requestor_id;

    /**
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getCryptogram()
    {
        return $this->cryptogram;
    }

    /**
     *
     * @param mixed $cryptogram
     */
    public function setCryptogram($cryptogram)
    {
        $this->cryptogram = $cryptogram;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getEci()
    {
        return $this->eci;
    }

    /**
     *
     * @param mixed $eci
     */
    public function setEci($eci)
    {
        $this->eci = $eci;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getRequestorId()
    {
        return $this->requestor_id;
    }

    /**
     *
     * @param mixed $requestor_id
     */
    public function setRequestorId($requestor_id)
    {
        $this->requestor_id = $requestor_id;

        return $this;
    }
}
?>
