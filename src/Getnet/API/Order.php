<?php
namespace Getnet\API;

/**
 * Class Order
 *
 * @package Getnet\API
 */
class Order implements \JsonSerializable {
    
    const PRODUCT_TYPE_CASH_CARRY       = "cash_carry";
    const PRODUCT_TYPE_DIGITAL_CONTENT  = "digital_content";
    const PRODUCT_TYPE_DIGITAL_GOODS    = "digital_goods";
    const PRODUCT_TYPE_DIGITAL_PHYSICAL = "digital_physical";
    const PRODUCT_TYPE_GIFT_CARD        = "gift_card";
    const PRODUCT_TYPE_PHISICAL_GOODS   = "physical_goods";
    const PRODUCT_TYPE_RENEW_SUBS       = "renew_subs";
    const PRODUCT_TYPE_SHAREWARE        = "shareware";
    const PRODUCT_TYPE_SERVICE          = "service";

    private $order_id;

    private $product_type;

    private $sales_tax = 0;
    

    /**
     * 
     * @param mixed $order_id
     */
    public function __construct($order_id = null) {
        $this->order_id = $order_id;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
    
    /**
     * @return mixed
     */
    public function getOrderId() {
        return $this->order_id;
    }

    /**
     * @param mixed $order_id
     */
    public function setOrderId($order_id) {
        $this->order_id = (string)$order_id;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductType() {
        return $this->product_type;
    }

    /**
     * @param mixed $product_type
     */
    public function setProductType($product_type) {
        $this->product_type = (string)$product_type;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSalesTax() {
        return $this->sales_tax;
    }

    /**
     * @param mixed $sales_tax
     */
    public function setSalesTax($sales_tax) {
        $this->sales_tax = (int)($sales_tax * 100);
        
        return $this;
    }
    
}
