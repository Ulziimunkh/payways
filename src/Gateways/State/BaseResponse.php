<?php

namespace Selmonal\Payways\Gateways\State;

use Selmonal\Payways\Exceptions\ConnectionException;
use Selmonal\Payways\Gateway;
use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;

abstract class BaseResponse extends Response
{
    /**
     * Construct BaseResponse.
     *
     * @param Gateway     $gateway
     * @param Transaction $transaction
     * @param array       $data
     */
    public function __construct(Gateway $gateway, Transaction $transaction, $data = [])
    {
        parent::__construct($gateway, $transaction, $data);

        if ($this->getCode() !== '00') {
            throw new ConnectionException($gateway, $this->getMessage(), $this->getCode());
        }
    }

    /**
     * Get response status.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->data->get('Response.Status');
    }

    /**
     * Get message for the respone.
     *
     * @return string|null
     */
    public function getMessage()
    {
        switch ($this->getCode()) {
            case '00': return 'successfully'; break;
            case '30': return 'message invalid format (no mandatory fields and etc.)'; break;
            case '10': return 'Internet shop has no access to the Create Order operation (or the Internet shop is not registered)'; break;
            case '54': return 'invalid operation'; break;
            case '96': return 'system error'; break;
        }
    }
}
