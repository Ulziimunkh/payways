<?php

namespace Selmonal\Payways\Gateways\Khan;

use Guzzle\Http\Client;
use Selmonal\Payways\Exceptions\ConnectionException;
use Selmonal\Payways\Gateway as BaseGateway;
use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;
use Illuminate\Config\Repository as Config;


class Gateway extends BaseGateway
{
    const REGISTER_URL = 'https://epp.khanbank.com/payment/rest/register.do';
    const VERIFY_URL   = 'https://epp.khanbank.com/payment/rest/getOrderStatus.do';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Config
     */
    private $config;

    /**
     * Gateway constructor.
     *
     * @param Client $client
     * @param Config $config
     */
    public function __construct(Client $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @return string
     */
    function getName()
    {
        return 'khan';
    }

    /**
     * @param Transaction $transaction
     * @return Response
     */
    public function sendProcess(Transaction $transaction)
    {
        $parameters = $this->getProcessParameters($transaction);

        $response = $this->send(static::REGISTER_URL, $parameters);

        if(! $response->isSuccessful()) {
            throw new ConnectionException($this, (string) $response->getBody());
        }

        $data = $response->json();

        if($data['errorCode'] != '0') {
            throw new ConnectionException($this, $data['errorMessage'], $data['errorCode']);
        }

        return new ProcessResponse($this, $transaction, $data);
    }

    /**
     * @param Transaction $transaction
     * @return Response
     * @throws ConnectionException
     */
    public function sendCompleteProcess(Transaction $transaction)
    {
        $parameters = [
            'userName' => $this->getUsername(),
            'password' => $this->getPassword(),
            'orderId'  => $transaction->getReference()
        ];

        $response = $this->send(static::VERIFY_URL, $parameters);

        if(! $response->isSuccessful()) {
            throw new ConnectionException($this, (string) $response->getBody());
        }

        $data = $response->json();

        if($data['ErrorCode'] != '0' && $data['ErrorCode'] != '2') {
            throw new ConnectionException($this, $data['ErrorMessage'], $data['ErrorCode']);
        }

        return new CompleteProcessResponse($this, $transaction, $data);
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->config->get('payways.gateways.khan.username');
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->config->get('payways.gateways.khan.password');
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        $uri = $this->config->get('payways.gateways.khan.returnUrl');

        return url($uri);
    }

    /**
     * @return array
     */
    public function getSupportedCurrencies()
    {
        return ['MNT'];
    }

    /**
     * @param $url
     * @param $parameters
     * @return \Guzzle\Http\Message\Response
     */
    private function send($url, $parameters)
    {
        return $this->client->get($url . '?' . http_build_query($parameters))->send();
    }

    /**
     * @param Transaction $transaction
     * @return array
     */
    private function getProcessParameters(Transaction $transaction)
    {
        return [
            'userName' => $this->getUsername(),
            'password' => $this->getPassword(),
            'amount' => $transaction->amount,
            'description' => $transaction->description,
            'orderNumber' => $transaction->getKey(),
            'currency' => $transaction->getCurrency()->getNumeric(),
            'jsonParams' => [
                'orderNumber' => $transaction->getKey()
            ],
            'returnUrl' => $this->getReturnUrl()
        ];
    }
}