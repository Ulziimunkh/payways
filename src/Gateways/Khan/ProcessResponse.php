<?php

namespace Selmonal\Payways\Gateways\Khan;

use Selmonal\Payways\RedirectResponseInterface;
use Selmonal\Payways\Response as BaseResponse;

class ProcessResponse extends BaseResponse implements RedirectResponseInterface
{
    /**
     * @return string
     */
    public function getStatus()
    {
        if (!$this->getTransactionReference()) {
            return BaseResponse::STATUS_DECLINED;
        }

        return BaseResponse::STATUS_PENDING;
    }

    /**
     * @return string
     */
    public function getCode()
    {
    }

    /**
     * @return string
     */
    public function getMessage()
    {
    }

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
        return !is_null($this->getTransactionReference());
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
        if (isset($this->data['formUrl'])) {
            return $this->data['formUrl'];
        }
    }

    /**
     * @return string
     */
    public function getTransactionReference()
    {
        if (isset($this->data['orderId'])) {
            return $this->data['orderId'];
        }
    }

    /**
     * @return array
     */
    public function getRedirectData()
    {
        return [];
    }
}
