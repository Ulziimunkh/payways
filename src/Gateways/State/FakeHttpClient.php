<?php

namespace Selmonal\Payways\Gateways\State;

use Selmonal\Xml\Xml;

class FakeHttpClient implements HttpClient
{
    private $status;
    private $orderId;
    private $orderStatus;
    private $sessionId;
    private $lastSent;

    public function send($content)
    {
        $xml = new Xml();
        $this->lastSent = $content;

        if (str_contains($content, 'CreateOrder')) {
            $xml->loadFromString('<?xml version="1.0" encoding="UTF-8"?>
			<TKKPG>
				<Response>
					<Operation>CreateOrder</Operation>
					<Status>'.$this->status.'</Status>
					<Order>
						<OrderID>'.$this->orderId.'</OrderID>
						<SessionID>'.$this->sessionId.'</SessionID>
						<URL></URL>
					</Order>
				</Response>
			</TKKPG>');
        }

        if (str_contains($content, 'GetOrderStatus')) {
            $xml->loadFromString('<?xml version="1.0" encoding="UTF-8"?>
			<TKKPG>
				<Response>
					<Operation>GetOrderStatus</Operation>
					<Status>'.$this->status.'</Status>
					<Order>
						<OrderID>'.$this->orderId.'</OrderID>
						<OrderStatus>'.$this->orderStatus.'</OrderStatus>
					</Order>
					<AdditionalInfo>
						<Receipt>BASE64-encode-info</Receipt>
					</AdditionalInfo>
				</Response>
			</TKKPG>');
        }

        return $xml;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    public function lastSent()
    {
        return $this->lastSent;
    }
}
