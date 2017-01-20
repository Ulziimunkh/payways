<?php

namespace Selmonal\Payways\Gateways\State;

use Selmonal\Payways\Response;

class ProcessResponse extends Response
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
     * @return string|null
     */
    public function getMessage()
    {
        switch ($this->getCode()) 
        {
            case '00' : return 'successfully'; break;
            case '30' : return 'message invalid format (no mandatory fields and etc.)'; break;
            case '10' : return 'Internet shop has no access to the Create Order operation (or the Internet shop is not registered)'; break;
            case '54' : return 'invalid operation'; break;
            case '96' : return 'system error'; break;
        }
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return $this->data->get('Response.Status');
    }

    /**
     * @return string
     */
    public function getTransactionReference()
    {
        return json_encode([
            'orderId' => $this->data->get('Response.Order.OrderID'),
            'sessionId' => $this->data->get('Response.Order.SessionID'),
        ]);
    }
}