<?php

namespace Selmonal\Payways\Gateways\State;

use Selmonal\Payways\Response;

class CompleteProcessResponse extends BaseResponse
{
    /**
     * @return string
     */
    public function getStatus()
    {
        $status = strtolower($this->data->get('Response.Order.OrderStatus'));

        if ($status === 'approved') {
            return Response::STATUS_APPROVED;
        } elseif ($status === 'canceled') {
            return Response::STATUS_CANCELLED;
        } elseif ($status === 'declined') {
            return Response::STATUS_DECLINED;
        } elseif ($status === 'created' || $status === 'on-payment') {
            return Response::STATUS_PENDING;
        }
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getStatus() === Response::STATUS_APPROVED;
    }
}
