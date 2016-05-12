<?php

namespace Selmonal\Payways;

interface BillableInterface
{
    /**
 * @return float
 */
    public function getAmount();

    /**
     * @return integer
     */
    public function getCurrency();

    /**
     * @return string
     */
    public function getDescription();
}
