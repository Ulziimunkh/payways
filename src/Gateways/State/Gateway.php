<?php

namespace Selmonal\Payways\Gateways\State;

use Selmonal\Payways\Gateway as AbstractGateway;
use Selmonal\Payways\Transaction;

class Gateway extends AbstractGateway
{
    /**
     * @var HttpClient
     */
    private $client;

    private $merchantId;

    /**
     * Construct Gateway.
     *
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'state';
    }

    /**
     * @param Transaction $transaction
     *
     * @throws ConnectionException
     *
     * @return Response
     */
    public function sendProcess(Transaction $transaction)
    {
        $xml = $this->client->send('<?xml version="1.0" encoding="UTF-8"?>
        <TKKPG>
            <Request>
                <Operation>CreateOrder</Operation>
                <Language>EN</Language>
                <Order>
                    <Merchant>'.$this->merchantId.'</Merchant>
                    <Amount>'.((int) $transaction->amount * 100).'</Amount>
                    <Currency>'.$transaction->getCurrency()->getNumeric().'</Currency>
                    <Description>'.$transaction->description.'</Description>
                    <ApproveURL>'.$this->getCallbackUrl($transaction).'</ApproveURL>
                    <CancelURL>'.$this->getCallbackUrl($transaction).'</CancelURL>
                    <DeclineURL>'.$this->getCallbackUrl($transaction).'</DeclineURL>
                </Order>
            </Request>
        </TKKPG>');

        return new ProcessResponse($this, $transaction, $xml);
    }

    /**
     * @param Transaction $transaction
     *
     * @throws ConnectionException
     *
     * @return Response
     */
    public function sendCompleteProcess(Transaction $transaction)
    {
        $requestData = json_decode($transaction->reference, true);

        $xml = $this->client->send("<?xml version='1.0' encoding='UTF-8'?>
        <TKKPG>
            <Request>
                <Operation>GetOrderStatus</Operation>
                <Language>EN</Language>
                <Order>
                    <Merchant>".$this->merchantId.'</Merchant>
                    <OrderID>'.$requestData['orderId'].'</OrderID>
                </Order>
                <SessionID>'.$requestData['sessionId'].'</SessionID>
            </Request>
        </TKKPG>');

        return new CompleteProcessResponse($this, $transaction, $xml);
    }

    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    public function getCallbackUrl($transaction)
    {
        return sprintf('%s?trans_id=%s', $this->callbackUrl, $transaction->id);
    }

    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }
}
