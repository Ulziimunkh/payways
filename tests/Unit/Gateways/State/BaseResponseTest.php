<?php

use Selmonal\Payways\Exceptions\ConnectionException;
use Selmonal\Payways\Gateways\State\BaseResponse;
use Selmonal\Payways\Gateways\State\FakeHttpClient;
use Selmonal\Payways\Gateways\State\Gateway;
use Selmonal\Payways\Transaction;

class BaseResponseTest extends TestCase
{
	private $client;

	public function setUp()
	{
		parent::setUp();

		$this->client = new FakeHttpClient;
		$this->gateway = new Gateway($this->client);
	}

	/** @test */
	public function it_creates_instance_with_valid_xml()
	{
		$this->client->setStatus('00');

		$response = new BaseResponseTestStub($this->gateway, new Transaction, $this->client->send('CreateOrder'));

		$this->assertEquals('00', $response->getCode());
	}

    /** @test */
    public function it_should_throw_an_exception_if_the_response_status_is_not_success()
    {
    	$this->client->setStatus('96');

    	try {
    		$response = new BaseResponseTestStub($this->gateway, new Transaction, $this->client->send('CreateOrder'));
    	} catch (ConnectionException $exception) {
    		return;
    	}

    	$this->fail('An exception did not throw.');
    }
}

class BaseResponseTestStub extends BaseResponse
{
	public function isSuccessful() {}
	public function getStatus() {}
}