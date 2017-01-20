<?php

use Selmonal\Payways\Gateways\State\FakeHttpClient;
use Selmonal\Xml\Xml;

class FakeHttpClientTest extends TestCase
{
    /** @test */
    public function can_return_for_create_order()
    {
    	$client = new FakeHttpClient;
    	$client->setStatus('00');
    	$client->setOrderId('123456');
    	$client->setSessionId('F26C1C280BFAB9828456573AB9BCEB49');

    	$xml = $client->send('CreateOrder');

    	$this->assertInstanceOf(Xml::class, $xml);
    	$this->assertEquals('CreateOrder', $xml->get('Response.Operation'));
    	$this->assertEquals('00', $xml->get('Response.Status'));
    	$this->assertEquals('123456', $xml->get('Response.Order.OrderID'));
    	$this->assertEquals('F26C1C280BFAB9828456573AB9BCEB49', $xml->get('Response.Order.SessionID'));
    	$this->assertEquals('CreateOrder', $client->lastSent());
    }

    /** @test */
    public function can_return_for_get_order_status()
    {
    	$client = new FakeHttpClient;
    	$client->setStatus('00');
    	$client->setOrderStatus('CREATED');

    	$xml = $client->send('GetOrderStatus');

    	$this->assertInstanceOf(Xml::class, $xml);
    	$this->assertEquals('GetOrderStatus', $xml->get('Response.Operation'));
    	$this->assertEquals('00', $xml->get('Response.Status'));
    	$this->assertEquals('CREATED', $xml->get('Response.Order.OrderStatus'));
    	$this->assertEquals('GetOrderStatus', $client->lastSent());
    }
}