<?php

namespace Getnet\API;

class BaseResponse implements \JsonSerializable
{
    public $payment_id;

    public $seller_id;

    public $amount;

    public $currency;

    public $order_id;

    public $status;

    public $received_at;

    public $message;

    public $error_message;

    public $error_code;

    public $description;

    public $description_detail;

    public $status_code;

    public $responseJSON;

    public $status_label;

    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    /**
     * TODO refactor and mapper individual and remove public props.
     *
     * @param array $json
     *
     * @return $this
     */
    public function mapperJson($json)
    {
        if (is_array($json)) {
            array_walk_recursive($json, function ($value, $key) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            });
        }

        $this->setResponseJSON($json);

        return $this;
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @return BaseResponse
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;

        return $this;
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @return BaseResponse
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;

        return $this;
    }

    public function getDescriptionDetail()
    {
        return $this->description_detail;
    }

    /**
     * @return BaseResponse
     */
    public function setDescriptionDetail($description_detail)
    {
        $this->description_detail = $description_detail;

        return $this;
    }

    public function getErrorDescription()
    {
        return $this->description;
    }

    /**
     * @return BaseResponse
     */
    public function setErrorDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getPaymentId()
    {
        return $this->payment_id;
    }

    public function setPaymentId($payment_id)
    {
        $this->payment_id = $payment_id;

        return $this;
    }

    public function getSellerId()
    {
        return $this->seller_id;
    }

    public function setSellerId($seller_id)
    {
        $this->seller_id = $seller_id;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getStatus()
    {
        if ($this->status_code == 201) {
            $this->status = Transaction::STATUS_AUTHORIZED;
        } elseif ($this->status_code == 202) {
            $this->status = Transaction::STATUS_AUTHORIZED;
        } elseif ($this->status_code == 402) {
            $this->status = Transaction::STATUS_DENIED;
        } elseif ($this->status_code == 400) {
            $this->status = Transaction::STATUS_ERROR;
        } elseif ($this->status_code == 402) {
            $this->status = Transaction::STATUS_ERROR;
        } elseif ($this->status_code == 500) {
            $this->status = Transaction::STATUS_ERROR;
        } elseif ($this->status_code == 1 || (property_exists($this, 'redirect_url') && isset($this->redirect_url))) {
            $this->status = Transaction::STATUS_PENDING;
        } elseif (isset($this->status_label)) {
            // TODO check why
            $this->status = $this->status_label;
        }

        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getReceivedAt()
    {
        return $this->received_at;
    }

    public function setReceivedAt($received_at)
    {
        $this->received_at = $received_at;

        return $this;
    }

    public function getResponseJSON()
    {
        return $this->responseJSON;
    }

    public function setResponseJSON($array)
    {
        $this->responseJSON = json_encode($array, JSON_PRETTY_PRINT);

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getError_code()
    {
        return $this->error_code;
    }

    public function setError_code($error_code)
    {
        $this->error_code = $error_code;

        return $this;
    }
}
