<?php

namespace Selmonal\Payways\Gateways\Log;

use Selmonal\Payways\Response as BaseResponse;

class Response extends BaseResponse
{
    public function isSuccessful()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return BaseResponse::STATUS_APPROVED;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return '0';
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return 'Log transaction has processed successfully';
    }

    /**
     * @return array
     */
    public function getData()
    {
        return null;
    }
}