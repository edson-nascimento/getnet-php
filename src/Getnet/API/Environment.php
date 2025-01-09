<?php

namespace Getnet\API;

class Environment
{
    private $api;

    /**
     * @param string $api
     */
    private function __construct($api)
    {
        $this->api = $api;
    }

    /**
     * @return Environment
     */
    public static function sandbox()
    {
        return new Environment('https://api-sandbox.getnet.com.br');
    }

    /**
     * @return Environment
     */
    public static function homolog()
    {
        return new Environment('https://api-homologacao.getnet.com.br');
    }

    /**
     * @return Environment
     */
    public static function production()
    {
        return new Environment('https://api.getnet.com.br');
    }

    /**
     * Gets the environment's Api URL.
     *
     * @return string the Api URL
     */
    public function getApiUrl()
    {
        return $this->api;
    }
}
