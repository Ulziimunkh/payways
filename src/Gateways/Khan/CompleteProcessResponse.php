<?php

namespace Selmonal\Payways\Gateways\Khan;

use Selmonal\Payways\Response as BaseResponse;

class CompleteProcessResponse extends BaseResponse
{
    /**
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->getStatus() == BaseResponse::STATUS_APPROVED;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        if ($this->getCode() == '2') {
            return BaseResponse::STATUS_APPROVED;
        }

        if ($this->getCode() == '3') {
            return BaseResponse::STATUS_CANCELLED;
        }

        return BaseResponse::STATUS_DECLINED;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->data['OrderStatus'];
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        switch ($this->getCode()) {
            case '0' : return 'Order registered, but not paid off.'; break;
            case '1' : return 'Pre-authorisation of order amount was held (for two-stage payment)'; break;
            case '2' : return 'Order amount is fully authorized.'; break;
            case '3' : return 'Authorization canceled.'; break;
            case '4' : return 'Transaction was refunded.'; break;
            case '5' : return 'Initiated authorization using ACS of the issuer bank.'; break;
            case '6' : return 'Authorization declined.'; break;
        }
    }
}
