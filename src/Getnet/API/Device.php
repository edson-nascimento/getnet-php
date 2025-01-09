<?php

namespace Getnet\API;

class Device implements \JsonSerializable
{
    use TraitEntity;

    private $device_id;

    private $ip_address;

    /**
     * @param string|null $device_id
     */
    public function __construct($device_id = null)
    {
        $this->device_id = $device_id;
    }

    public function getDeviceId()
    {
        return $this->device_id;
    }

    public function setDeviceId($device_id)
    {
        $this->device_id = (string) $device_id;

        return $this;
    }

    public function getIpAddress()
    {
        return $this->ip_address;
    }

    public function setIpAddress($ip_address)
    {
        $this->ip_address = (string) $ip_address;

        return $this;
    }
}
