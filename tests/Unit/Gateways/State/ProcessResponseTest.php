<?php

use Selmonal\Payways\Gateways\State\FakeHttpClient;
use Selmonal\Payways\Gateways\State\Gateway;
use Selmonal\Payways\Gateways\State\ProcessResponse;
use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;

class ProcessResponseTest extends TestCase
{
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new FakeHttpClient();
        $this->gateway = new Gateway($this->client);
    }

    /** @test */
    public function response_with_00_status_is_successful()
    {
        $this->client->setStatus('00');

        $response = new ProcessResponse($this->gateway, new Transaction(), $this->client->send('CreateOrder'));

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals(Response::STATUS_PENDING, $response->getStatus());
        $this->assertEquals('00', $response->getCode());
    }
}
