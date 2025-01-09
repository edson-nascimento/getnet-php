<?php

namespace Getnet\API\Service;

use Getnet\API\Getnet;

abstract class BaseService
{
    private $getnetService;

    public function __construct(Getnet $getnet)
    {
        $this->getnetService = $getnet;
    }

    protected function getnetService(): Getnet
    {
        return $this->getnetService;
    }
}
