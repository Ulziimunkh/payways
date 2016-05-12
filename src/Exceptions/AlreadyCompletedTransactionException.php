<?php

namespace Selmonal\Payways\Exceptions;


use Selmonal\Payways\Gateway;
use Selmonal\Payways\Transaction;

class AlreadyCompletedTransactionException extends GatewayException
{
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * ConnectionException constructor.
     *
     * @param Transaction $transaction
     * @param Gateway $gateway
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(Transaction $transaction, Gateway $gateway, $message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($gateway, $message, $code, $previous);

        $this->transaction = $transaction;
    }

    /**
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}