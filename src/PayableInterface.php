<?php

namespace Selmonal\Payways;

interface PayableInterface
{
    /**
     * @return float
     */
    public function getPaymentAmount();

    /**
     * @return integer
     */
    public function getPaymentCurrency();

    /**
     * @return string
     */
    public function getPaymentDescription();

    /**
     * @return integer
     */
    public function getKey();
}
