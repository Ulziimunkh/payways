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
     * @var BillableInterface
     */
    private $billable;

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
     * @param BillableInterface $billable
     */
    public function billable(BillableInterface $billable)
    {
        $this->billable = $billable;
    }

    /**
     * Process the builder and create a new transaction.
     *
     * @return static
     */
    public function create()
    {
        $transaction = Transaction::make($this->gateway, $this->attributes);

        if($this->billable) {
            $transaction->setBillable($this->billable);
        }

        $transaction->save();

        return $transaction;
    }
}