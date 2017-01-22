<?php

use Selmonal\Payways\Gateways\State\CompleteProcessResponse;
use Selmonal\Payways\Gateways\State\FakeHttpClient;
use Selmonal\Payways\Gateways\State\Gateway;
use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;

class CompleteProcessResponseTest extends TestCase
{
    private $client;

	public function setUp()
	{
		parent::setUp();

		$this->client = new FakeHttpClient;
		$this->client->setStatus('00');
		$this->gateway = new Gateway($this->client);
	}

	/** @test */
	public function returning_order_status()
	{
		$this->client->setOrderStatus('APPROVED');
		$response = new CompleteProcessResponse($this->gateway, new Transaction, $this->client->send('GetOrderStatus'));
		$this->assertEquals(Response::STATUS_APPROVED, $response->getStatus());
		$this->assertTrue($response->isSuccessful());
		$this->assertFalse($response->isRedirect());

		$this->client->setOrderStatus('CANCELED');
		$response = new CompleteProcessResponse($this->gateway, new Transaction, $this->client->send('GetOrderStatus'));
		$this->assertEquals(Response::STATUS_CANCELLED, $response->getStatus());
		$this->assertFalse($response->isSuccessful());
		$this->assertFalse($response->isRedirect());

		$this->client->setOrderStatus('DECLINED');
		$response = new CompleteProcessResponse($this->gateway, new Transaction, $this->client->send('GetOrderStatus'));
		$this->assertEquals(Response::STATUS_DECLINED, $response->getStatus());
		$this->assertFalse($response->isSuccessful());
		$this->assertFalse($response->isRedirect());

		$this->client->setOrderStatus('CREATED');
		$response = new CompleteProcessResponse($this->gateway, new Transaction, $this->client->send('GetOrderStatus'));
		$this->assertEquals(Response::STATUS_PENDING, $response->getStatus());
		$this->assertFalse($response->isSuccessful());
		$this->assertFalse($response->isRedirect());

		$this->client->setOrderStatus('ON-PAYMENT');
		$response = new CompleteProcessResponse($this->gateway, new Transaction, $this->client->send('GetOrderStatus'));
		$this->assertEquals(Response::STATUS_PENDING, $response->getStatus());
		$this->assertFalse($response->isSuccessful());
		$this->assertFalse($response->isRedirect());
	}
}