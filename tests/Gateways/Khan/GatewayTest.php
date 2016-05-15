<?php

namespace Selmonal\Payways\Gateways\Khan;

use Selmonal\Payways\TestCase;

class GatewayTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->gateway = new Gateway($this->getHttpClient());
    }

    public function testProcessSuccess()
    {
        $this->setMockHttpResponse('SuccessProcess.txt');
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
        $this->setMockHttpResponse('FailedProcess.txt');
        $this->gateway->sendProcess($this->makeTransaction());
    }

    public function testCompleteProcessSuccess()
    {
        $this->setMockHttpResponse('SuccessCompleteProcess.txt');
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
        $this->setMockHttpResponse('FailedCompleteProcess.txt');
        $transaction = $this->makeTransaction();
        $transaction->reference = '560bc033-3ce8-40f5-a6e6-23e9b742df98';
        $response = $this->gateway->sendCompleteProcess($transaction);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(6, $response->getCode());
        $this->assertSame('Authorization declined.', $response->getMessage());
    }
}
