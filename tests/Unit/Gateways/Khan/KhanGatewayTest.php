<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Selmonal\Payways\Gateways\Khan\Gateway;

class KhanGatewayTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway();
    }

    public function setHttpResponse($code, $body)
    {
        $mock = new MockHandler([
            new Response($code, [], $body),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $this->gateway->setHttpClient($client);
    }

    public function testProcessSuccess()
    {
        $this->setHttpResponse(200, '{"errorCode":"0","orderId":"560bc033-3ce8-40f5-a6e6-23e9b742df98","formUrl":"https://epp.khanbank.com/payment/merchants/q2ozbx/payment_en.html?mdOrder=560bc033-3ce8-40f5-a6e6-23e9b742df98"}');

        $response = $this->gateway->sendProcess($this->makeTransaction());

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('560bc033-3ce8-40f5-a6e6-23e9b742df98', $response->getTransactionReference());
    }

    /**
     * @expectedException \Selmonal\Payways\Exceptions\ConnectionException
     * @expectedExceptionMessage Access denied
     */
    public function testProcessFailed()
    {
        $this->setHttpResponse(200, '{"errorCode":"5","errorMessage":"Access denied"}');
        $this->gateway->sendProcess($this->makeTransaction());
    }

    public function testCompleteProcessSuccess()
    {
        $this->setHttpResponse(200, '{"expiration":"201610","cardholderName":"TEST TEST","depositAmount":0,"currency":"496","authCode":2,"ErrorCode":"0","ErrorMessage":"Success","OrderStatus":2,"OrderNumber":"356","Pan":"555555**5555","Amount":9691,"Ip":"202.170.71.91"}');
        $transaction = $this->makeTransaction();
        $transaction->reference = '560bc033-3ce8-40f5-a6e6-23e9b742df98';
        $response = $this->gateway->sendCompleteProcess($transaction);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(2, $response->getCode());
        $this->assertSame('Order amount is fully authorized.', $response->getMessage());
    }

    public function testCompleteProcessFailed()
    {
        $this->setHttpResponse(200, '{"expiration":"201610","cardholderName":"TEST TEST","depositAmount":0,"currency":"496","authCode":2,"ErrorCode":"2","ErrorMessage":"Payment is declined","OrderStatus":6,"OrderNumber":"356","Pan":"555555**5555","Amount":9691,"Ip":"202.170.71.91"}');
        $transaction = $this->makeTransaction();
        $transaction->reference = '560bc033-3ce8-40f5-a6e6-23e9b742df98';
        $response = $this->gateway->sendCompleteProcess($transaction);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(6, $response->getCode());
        $this->assertSame('Authorization declined.', $response->getMessage());
    }
}
