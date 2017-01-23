<?php

namespace Selmonal\Payways\Gateways\State;

use Selmonal\Payways\RedirectResponseInterface;
use Selmonal\Payways\Response;

class ProcessResponse extends BaseResponse implements RedirectResponseInterface
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
        return $this->getCode() === '00';
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        if ($this->isRedirect()) {
            return Response::STATUS_PENDING;
        }

        return Response::STATUS_DECLINED;
    }

    /**
     * @return string
     */
    public function getTransactionReference()
    {
        return json_encode([
            'orderId'   => $this->data->get('Response.Order.OrderID'),
            'sessionId' => $this->data->get('Response.Order.SessionID'),
        ]);
    }

    /**
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->data->get('Response.Order.URL');
    }

    /**
     * @return array
     */
    public function getRedirectData()
    {
        return [];
    }
}
