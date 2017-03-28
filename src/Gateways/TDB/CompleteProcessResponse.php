<?php

namespace Selmonal\Payways\Gateways\TDB;

use DOMDocument;
use Selmonal\Payways\Exceptions\GatewayException;
use Selmonal\Payways\Gateways\TDB\Gateway;
use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;
use Selmonal\Xml\Xml;

class CompleteProcessResponse extends Response
{
	protected $xml;

	/**
     * CompleteProcessResponse constructor.
     *
     * @param Gateway     $gateway
     * @param Transaction $transaction
     * @param mixed       $data
     */
    public function __construct(Gateway $gateway, Transaction $transaction, $data = [])
    {
        parent::__construct($gateway, $transaction, $data);

    	$this->xml = new Xml;
    	$this->xml->loadFromString($data);

        // Validate if transaction has approved.
        if ($this->isSuccessful()) {
            $this->validateXmlData($this->data);
        }
    }

	/**
     * @return bool
     */
    public function isSuccessful()
    {
    	return $this->getStatus() === Response::STATUS_APPROVED;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
    	switch (strtolower($this->xml->get('OrderStatus'))) {
    		case 'approved': return Response::STATUS_APPROVED; break;
            case 'canceled': return Response::STATUS_CANCELLED; break;
    		default: return Response::STATUS_DECLINED; break;
    	}
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->xml->get('ResponseCode');
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->xml->get('ResponseDescription');
    }

    /**
     * Validate the given xml response.
     * 
     * @param  string $xml
     */
    private function validateXmlData($xml)
    {
        # Load Signed XML
        $xmlDoc = new DOMDocument();
        $xmlDoc->loadXML($xml);

        # Get DigestValue
        $sigDigest = $xmlDoc->documentElement->getElementsByTagName("DigestValue")->item(0)->nodeValue;
        $signedInfo = $xmlDoc->getElementsByTagName("SignedInfo")->item(0)->C14N(true, true);

        # Get SignatureValue
        $signature = base64_decode($xmlDoc->documentElement->getElementsByTagName("SignatureValue")->item(0)->nodeValue);
        
        # Check Certificate
        $ok = openssl_verify($signedInfo, $signature, $this->publicCertificate(), OPENSSL_ALGO_SHA1);

        if ($ok != 1) {
            throw new GatewayException($this->getGateway(), 'Invalid certificate.');
        }
        
        # Remove Signature from XML
        $elm=$xmlDoc->documentElement->getElementsByTagName("Signature")->item(0);
        $xmlDoc->documentElement->removeChild($elm);
        
        # Generate Digest Value from XML Data
        $xmlDigest = base64_encode(sha1($xmlDoc->documentElement->C14N(), true));
        
        # Check Generate Digest
        if ($sigDigest != $xmlDigest) {
            throw new GatewayException($this->getGateway(), 'Invalid XML Data.');
        }
    }

    /**
     * Get the public certificate.
     * 
     * @return string
     */
    public function publicCertificate()
    {
        $cert = file_get_contents($this->publicCertificateFilePath());

        return  openssl_pkey_get_public($cert);
    }

    /**
     * Get the public certificate file path.
     * 
     * @return string
     */
    private function publicCertificateFilePath()
    {
        return config('payways.gateways.tdb.public_cert');
    }
}