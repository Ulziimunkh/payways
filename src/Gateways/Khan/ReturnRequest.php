<?php

namespace Selmonal\Payways\Gateways\Khan;


use Illuminate\Http\Request;
use Selmonal\Payways\Transaction;

class ReturnRequest extends Request
{
    /**
     * Get the requested order id.
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->get('orderId');
    }

    /**
     * Get the requested transaction.
     *
     * @return Transaction
     */
    public function getTransaction()
    {
        return Transaction::findByReference($this->getOrderId(), 'khan');
    }
}