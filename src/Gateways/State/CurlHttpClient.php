<?php

namespace Selmonal\Payways\Gateways\State;

use Selmonal\Xml\Xml;

class CurlHttpClient
{
    /**
     * @var string
     */
    protected $serverUrl;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * Create new HttpClient instance.
     *
     * @param string $serverUrl
     * @param string $username
     * @param string $password
     */
    public function __construct($serverUrl, $username, $password)
    {
        $this->serverUrl = $serverUrl;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param $content
     *
     * @throws ConnectionFailedException
     *
     * @return mixed
     */
    public function send($content)
    {
        $ch = $this->configureCurl($content);

        // execute the connexion
        $ret = curl_exec($ch);

        if ($ret === false || ($code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) != 200) {
            throw new ConnectionFailedException("http request status code [{$code}] ");
        }

        $xml = new Xml();
        $xml->loadFromString($ret);

        return new Xml($xml);
    }

    /**
     * @param $content
     *
     * @return resource
     */
    private function configureCurl($content)
    {
        $ch = curl_init();

        // Set parameters
        curl_setopt($ch, CURLOPT_URL, $this->serverUrl);

        // Return a variable instead of posting it directly
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Active the POST method
        curl_setopt($ch, CURLOPT_POST, 1);

        // Request
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);

        return $ch;
    }
}
