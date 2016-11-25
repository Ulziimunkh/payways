<?php

namespace Selmonal\Payways\Exceptions;

use RuntimeException;
use Selmonal\Payways\Gateway;

class GatewayException extends RuntimeException
{
    /**
     * @var Gateway
     */
    private $gateway;

    /**
     * ConnectionException constructor.
     *
     * @param Gateway        $gateway
     * @param string         $message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct(Gateway $gateway, $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->gateway = $gateway;
    }

    /**
     * @return Gateway
     */
    public function getGateway()
    {
        return $this->gateway;
    }
}
