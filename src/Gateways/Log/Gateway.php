<?php

namespace Selmonal\Payways\Gateways\Log;

use Psr\Log\LoggerInterface;
use Selmonal\Payways\Gateway as BaseGateway;
use Selmonal\Payways\Transaction;

class Gateway extends BaseGateway
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Gateway constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'log';
    }

    /**
     * @param Transaction $transaction
     * @return Response
     */
    function sendProcess(Transaction $transaction)
    {
        $this->logger->info("Transaction [{$transaction->getKey()}] has processed. Amount: {$transaction->amount}, Currency: {$transaction->currency}, Description: {$transaction->description}");

        return new Response($this, $transaction, []);
    }

    /**
     * @param Transaction $transaction
     * @return Response
     */
    function sendCompleteProcess(Transaction $transaction)
    {
        $this->logger->info("Transaction [{$transaction->getKey()}] has completed. Amount: {$transaction->amount}, Currency: {$transaction->currency}, Description: {$transaction->description}");

        return new Response($this, $transaction, []);
    }

    /**
     * @return array
     */
    public function getSupportedCurrencies()
    {
        return ['MNT'];
    }
}