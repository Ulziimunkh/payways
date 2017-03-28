<?php

namespace Selmonal\Payways\Gateways\TDB;

use Selmonal\Payways\RedirectResponseInterface;
use Selmonal\Payways\Response;

class ProcessResponse extends Response implements RedirectResponseInterface
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return Response::STATUS_PENDING;
    }

    /**
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return config('payways.gateways.tdb.server');
    }

    /**
     * @return array
     */
    public function getRedirectData()
    {
        return [
            'ReturnURLApprove' => url(config('payways.gateways.tdb.returnUrl')),
            'ReturnURLDecline' => url(config('payways.gateways.tdb.returnUrl')),
            'PurchaseAmount'   => (int) $this->getTransaction()->getAmount() * 100,
            'merid'            => $this->getGateway()->getMerchantId(),
            'Currency'         => $this->getTransaction()->getCurrency()->getNumeric(),
            'OrderID'          => $this->getTransaction()->getKey(),
            'Description'      => $this->getTransaction()->description,
            'trans_id'         => $this->getTransaction()->getKey(),
        ];
    }
}
