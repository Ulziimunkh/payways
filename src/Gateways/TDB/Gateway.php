<?php

namespace Selmonal\Payways\Gateways\TDB;

use Selmonal\Payways\Gateway as BaseGateway;
use Selmonal\Payways\Transaction;

class Gateway extends BaseGateway
{
    private $merchantId;
    private $password;

    public function __construct($merchantId, $password)
    {
        $this->merchantId = $merchantId;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tdb';
    }

    /**
     * @param Transaction $transaction
     *
     * @throws ConnectionException
     *
     * @return Response
     */
    public function sendProcess(Transaction $transaction)
    {
        return new ProcessResponse($this, $transaction);
    }

    /**
     * @param Transaction $transaction
     *
     * @throws ConnectionException
     *
     * @return Response
     */
    public function sendCompleteProcess(Transaction $transaction)
    {
        return new CompleteProcessResponse($this, $transaction, request('xmlmsg'));
    }

    /**
     * Gets the value of merchantId.
     *
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }
}
