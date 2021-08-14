<?php
namespace Getnet\API;

/**
 * Class Pix
 *
 * @package Getnet\API
 */
class Pix implements \JsonSerializable {

    private $document_number;


    /**
     * Pix constructor.
     *
     */
    public function __construct() {
        
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

    /**
     *
     * @return mixed
     */
    public function getDocumentNumber() {
        return $this->document_number;
    }

    /**
     *
     * @param mixed $document_number
     * @return Pix
     */
    public function setDocumentNumber($document_number) {
        $this->document_number = (string)$document_number;

        return $this;
    }

}