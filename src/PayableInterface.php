<?php

namespace Selmonal\Payways;

interface PayableInterface
{
    /**
     * @return float
     */
    public function getPaymentAmount();

    /**
     * @return int
     */
    public function getPaymentCurrency();

    /**
     * @return string
     */
    public function getPaymentDescription();

    /**
     * @return int
     */
    public function getKey();
}
