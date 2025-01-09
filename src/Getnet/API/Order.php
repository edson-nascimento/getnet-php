<?php

namespace Getnet\API;

class Order implements \JsonSerializable
{
    use TraitEntity;

    public const PRODUCT_TYPE_CASH_CARRY = 'cash_carry';

    public const PRODUCT_TYPE_DIGITAL_CONTENT = 'digital_content';

    public const PRODUCT_TYPE_DIGITAL_GOODS = 'digital_goods';

    public const PRODUCT_TYPE_DIGITAL_PHYSICAL = 'digital_physical';

    public const PRODUCT_TYPE_GIFT_CARD = 'gift_card';

    public const PRODUCT_TYPE_PHISICAL_GOODS = 'physical_goods';

    public const PRODUCT_TYPE_RENEW_SUBS = 'renew_subs';

    public const PRODUCT_TYPE_SHAREWARE = 'shareware';

    public const PRODUCT_TYPE_SERVICE = 'service';

    private $order_id;

    private $product_type;

    private $sales_tax = 0;

    /**
     * @param string|null $order_id
     */
    public function __construct($order_id = null)
    {
        $this->order_id = $order_id;
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function setOrderId($order_id)
    {
        $this->order_id = (string) $order_id;

        return $this;
    }

    public function getProductType()
    {
        return $this->product_type;
    }

    public function setProductType($product_type)
    {
        $this->product_type = (string) $product_type;

        return $this;
    }

    public function getSalesTax()
    {
        return $this->sales_tax;
    }

    public function setSalesTax($sales_tax)
    {
        $this->sales_tax = (int) ($sales_tax * 100);

        return $this;
    }
}
