<?php

namespace Selmonal\Payways;


use Selmonal\Payways\Exceptions\AlreadyCompletedTransactionException;
use Selmonal\Payways\Exceptions\ConnectionException;
use Selmonal\Payways\Exceptions\GatewayException;

abstract class Gateway
{
    /**
     * @return string
     */
    abstract function getName();

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
     * Complete
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
     * @param array $attributes
     * @return Transaction
     */
    public function newTransaction(array $attributes = [])
    {
        return new TransactionBuilder($this, $attributes);
    }

    /**
     * @param $gatewayName
     * @return GatewayInterface
     */
    public static function make($gatewayName)
    {
        return \App::make('payways.' . $gatewayName);
    }

    /**
     * @param Transaction $transaction
     * @throws AlreadyCompletedTransactionException
     */
    private function beforeSend(Transaction $transaction)
    {
        if (!$transaction->isPending()) {
            throw new AlreadyCompletedTransactionException($transaction, $this, 'Transaction is not pending.');
        }
    }
}
