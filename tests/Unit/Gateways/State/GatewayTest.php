<?php

use Selmonal\Payways\Gateways\State\CompleteProcessResponse;
use Selmonal\Payways\Gateways\State\FakeHttpClient;
use Selmonal\Payways\Gateways\State\Gateway;
use Selmonal\Payways\Gateways\State\ProcessResponse;
use Selmonal\Payways\Transaction;
use Selmonal\Xml\Xml;

class StateGatewayTest extends TestCase
{
    private $client;
    private $gateway;
    private $transaction;

    public function setUp()
    {
        parent::setUp();

        $this->client = new FakeHttpClient();
        $this->client->setStatus('00');

        $this->gateway = new Gateway($this->client);
        $this->gateway->setSupportedCurrencies(['mnt']);
        $this->gateway->setMerchantId('merchant');
        $this->gateway->setCallbackUrl('callback-url');

        $this->transaction = Transaction::make($this->gateway, [
            'amount' => 45000, 'currency' => 'MNT', 'description' => 'Test description.',
        ]);

        $this->transaction->save();
    }

    /** @test */
    public function sending_correct_xml_for_create_order_request()
    {
        $response = $this->gateway->process($this->transaction);

        $this->assertNotNull($this->client->lastSent());
        $xml = new Xml();
        $xml->loadFromString($this->client->lastSent());
        $this->assertEquals('4500000', $xml->get('Request.Order.Amount'));
        $this->assertEquals('merchant', $xml->get('Request.Order.Merchant'));
        $this->assertEquals('callback-url?trans_id='.$this->transaction->id, $xml->get('Request.Order.ApproveURL'));
        $this->assertEquals('callback-url?trans_id='.$this->transaction->id, $xml->get('Request.Order.CancelURL'));
        $this->assertEquals('callback-url?trans_id='.$this->transaction->id, $xml->get('Request.Order.DeclineURL'));
        $this->assertEquals('Test description.', $xml->get('Request.Order.Description'));
        $this->assertEquals(496, $xml->get('Request.Order.Currency'));

        $this->assertTrue($response instanceof ProcessResponse);
        $this->assertEquals($this->client->send('CreateOrder'), $response->getData());
    }

    /** @test */
    public function sending_correct_xml_for_get_order_status()
    {
        $this->client->setOrderStatus('APPROVED');

        $this->transaction->reference = json_encode([
            'orderId'   => '12334123412',
            'sessionId' => 'WQE1231O23H1UH231IU2H31O23HOI32',
        ]);

        $response = $this->gateway->completeProcess($this->transaction);

        $this->assertNotNull($this->client->lastSent());
        $xml = new Xml();
        $xml->loadFromString($this->client->lastSent());
        $this->assertEquals('merchant', $xml->get('Request.Order.Merchant'));
        $this->assertEquals('12334123412', $xml->get('Request.Order.OrderID'));
        $this->assertEquals('WQE1231O23H1UH231IU2H31O23HOI32', $xml->get('Request.SessionID'));

        $this->assertTrue($response instanceof CompleteProcessResponse);
        $this->assertEquals($this->client->send('GetOrderStatus'), $response->getData());
    }
}
