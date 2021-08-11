<?php
namespace Getnet\API;

/**
 * Class PixRespose
 *
 * @package Getnet\API
 */
class PixRespose extends BaseResponse {

    public $additional_data;

    public function setAditionalData(PixAditionalDataResponse $data) {
        $this->additional_data = $data;
    }

    /**
     * 
     * @return PixAditionalDataResponse
     */
    public function getAditionalData() {
        return $this->additional_data;
    }
}

class PixAditionalDataResponse {

    public $transaction_id;
    public $qr_code;
    public $creation_date_qrcode;
    public $expiration_date_qrcode;
    public $psp_code;

    

}