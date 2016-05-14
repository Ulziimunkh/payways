<?php

namespace Selmonal\Payways;

use Selmonal\Payways\Exceptions\AlreadyCompletedTransactionException;
use Selmonal\Payways\Exceptions\ConnectionException;
use Selmonal\Payways\Exceptions\GatewayException;

abstract class Gateway
{
    /**
     * Make a new gateway instance.
     *
     * @param $gateway
     */
    public static function make($gateway)
    {
        return app('payways.' . $gateway);
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @param Transaction $transaction
     * @return Response
     * @throws ConnectionException
     */
    abstract public function sendProcess(Transaction $transaction);

    /**
     * @param Transaction $transaction
     * @return Response
     * @throws ConnectionException
     */
    public function sendCompleteProcess(Transaction $transaction)
    {
        throw new GatewayException($this, 'Gateway does not support completeProcess');
    }

    /**
     * Get supported currencies of the gateway.
     * Example: MNT, USD
     *
     * @return array
     */
    public function getSupportedCurrencies()
    {
        return [];
    }

    /**
     * Process the transaction.
     *
     * @param Transaction $transaction
     * @return ResponseInterface
     * @throws ConnectionException
     */
    public function process(Transaction $transaction)
    {
        $this->beforeSend($transaction);

        $response = $this->sendProcess($transaction);

        $transaction->setReference($response->getTransactionReference());

        $transaction->updateStatus($response);

        return $response;
    }

    /**
     * Complete the process of the off-site transaction.
     *
     * @param Transaction $transaction
     * @return ResponseInterface
     * @throws AlreadyCompleteException
     * @throws ConnectionException
     */
    public function completeProcess(Transaction $transaction)
    {
        $this->beforeSend($transaction);

        $response = $this->sendCompleteProcess($transaction);

        $transaction->updateStatus($response);

        return $response;
    }

    /**
     * Make a new transaction. It returns transaction
     * builder instance.
     *
     * @param array $attributes
     * @return Transaction
     */
    public function transaction(array $attributes = [])
    {
        return new TransactionBuilder($this, $attributes);
    }

    /**
     * @param Transaction $transaction
     * @throws \GatewayException
     */
    private function beforeSend(Transaction $transaction)
    {
        if (! in_array($transaction->getCurrency()->getCode(), $this->getSupportedCurrencies())) {
            throw new GatewayException($this, 'Unsupported currency exception');
        }

        if (! $transaction->isPending()) {
            throw new AlreadyCompletedTransactionException($transaction, $this, 'Transaction is not pending.');
        }
    }
}
