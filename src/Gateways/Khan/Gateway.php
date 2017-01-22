<?php

namespace Selmonal\Payways\Gateways\Khan;

use GuzzleHttp\Client;
use Selmonal\Payways\Exceptions\ConnectionException;
use Selmonal\Payways\Gateway as BaseGateway;
use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;

class Gateway extends BaseGateway
{
    const REGISTER_URL = 'https://epp.khanbank.com/payment/rest/register.do';
    const VERIFY_URL = 'https://epp.khanbank.com/payment/rest/getOrderStatus.do';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $returnUrl;

    /**
     * Gateway constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'khan';
    }

    /**
     * @param Transaction $transaction
     *
     * @return Response
     */
    public function sendProcess(Transaction $transaction)
    {
        $parameters = $this->getProcessParameters($transaction);

        $response = $this->send(static::REGISTER_URL, $parameters);

        if ($response->getStatusCode() !== 200) {
            throw new ConnectionException($this, (string) $response->getBody());
        }

        $data = $this->getData($response);

        if ($data['errorCode'] != '0') {
            throw new ConnectionException($this, $data['errorMessage'], $data['errorCode']);
        }

        return new ProcessResponse($this, $transaction, $data);
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
        $parameters = [
            'userName' => $this->getUsername(),
            'password' => $this->getPassword(),
            'orderId'  => $transaction->getReference(),
        ];

        $response = $this->send(static::VERIFY_URL, $parameters);

        if ($response->getStatusCode() !== 200) {
            throw new ConnectionException($this, (string) $response->getBody());
        }

        $data = $this->getData($response);

        if ($data['ErrorCode'] != '0' && $data['ErrorCode'] != '2') {
            throw new ConnectionException($this, $data['ErrorMessage'], $data['ErrorCode']);
        }

        return new CompleteProcessResponse($this, $transaction, $data);
    }

    /**
     * @param \GuzzleHttp\Psr7\Response $response
     *
     * @return array
     */
    public function getData($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $url
     * @param $parameters
     *
     * @return \Guzzle\Http\Message\Response
     */
    private function send($url, $parameters)
    {
        return $this->client->get($url.'?'.http_build_query($parameters));
    }

    /**
     * @param Transaction $transaction
     *
     * @return array
     */
    private function getProcessParameters(Transaction $transaction)
    {
        return [
            'userName'    => $this->getUsername(),
            'password'    => $this->getPassword(),
            'amount'      => $transaction->amount * 100,
            'description' => $transaction->description,
            'orderNumber' => $transaction->getKey(),
            'currency'    => $transaction->getCurrency()->getNumeric(),
            'jsonParams'  => [
                'orderNumber' => $transaction->getKey(),
            ],
            'returnUrl' => $this->getReturnUrl(),
        ];
    }

    /**
     * Set the http client.
     *
     * @param Client $client.
     */
    public function setHttpClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }
}
