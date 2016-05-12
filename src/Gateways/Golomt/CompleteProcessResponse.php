<?php

namespace Selmonal\Payways\Gateways\Golomt;

use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;

class CompleteProcessResponse extends Response
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $message;

    /**
     * CompleteProcessResponse constructor.
     *
     * @param Gateway $gateway
     * @param Transaction $transaction
     * @param string $status
     * @param string $code
     * @param string $message
     * @param array $data
     */
    public function __construct(Gateway $gateway, Transaction $transaction, $status, $code = '', $message = '', array $data = [])
    {
        parent::__construct($gateway, $transaction, $data);

        $this->status = $status;
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->getStatus() == Response::STATUS_APPROVED;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
