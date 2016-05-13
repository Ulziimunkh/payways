<?php

namespace Selmonal\Payways;

class TransactionBuilder
{
    /**
     * @var Gateway
     */
    private $gateway;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var PayableInterface
     */
    private $payable;

    /**
     * TransactionBuilder constructor.
     *
     * @param Gateway $gateway
     * @param array $attributes
     */
    public function __construct(Gateway $gateway, array $attributes = [])
    {
        $this->gateway = $gateway;
        $this->attributes = $attributes;
    }

    /**
     * @param PayableInterface $payable
     * @return $this
     */
    public function payable(PayableInterface $payable)
    {
        $this->payable = $payable;

        return $this;
    }

    /**
     * Process the builder and create a new transaction.
     *
     * @return static
     */
    public function create()
    {
        $transaction = Transaction::make($this->gateway, $this->attributes);

        if ($this->payable) {
            $transaction->setPayable($this->payable);
        }

        $transaction->save();

        return $transaction;
    }
}
