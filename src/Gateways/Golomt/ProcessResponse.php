<?php

namespace Selmonal\Payways\Gateways\Golomt;

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
        return 'https://m.egolomt.mn/billingnew/cardinfo.aspx';
    }

    /**
     * Always redirect.
     *
     * @return bool
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * @return array
     */
    public function getRedirectData()
    {
        return [
            'key_number'   => $this->getGateway()->getKeyNumber(),
            'trans_number' => $this->getTransaction()->getKey(),
            'trans_amount' => $this->getTransaction()->getAmount(),
            'lang_ind'     => 0,
            'subID'        => $this->getGateway()->getSubId(),
        ];
    }
}
