<?php

namespace Selmonal\Payways\Gateways\Golomt;

use Illuminate\Http\Request;
use RuntimeException;
use Selmonal\Payways\Exceptions\ConnectionException;
use Selmonal\Payways\Gateway as BaseGateway;
use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;
use Illuminate\Config\Repository as Config;

class Gateway extends BaseGateway
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Request
     */
    private $request;

    /**
     * Gateway constructor.
     *
     * @param Config $config
     * @param Request $request
     */
    public function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'golomt';
    }

    /**
     * @param Transaction $transaction
     * @return Response
     */
    public function sendProcess(Transaction $transaction)
    {
        return new ProcessResponse($this, $transaction);
    }

    /**
     * @param Transaction $transaction
     * @return CompleteProcessResponse
     * @throws ConnectionException
     */
    public function sendCompleteProcess(Transaction $transaction)
    {
        // Хэрвээ банкнаас буцаан дуудсан хүсэлт байгаад тэр нь амжилтгүй төлөвтэй байвал гүйлгээг
        // шууд амжилтгүй гэж үзэн soap шалгалт хийлгүйгээр Response буцаана.
        if ($this->request->get('success') == '1' && $this->request->get('trans_number') == $transaction->getKey()) {
            return new CompleteProcessResponse(
                $this,
                $transaction,
                Response::STATUS_DECLINED,
                $this->request->get('error_code'),
                $this->request->get('error_desc'),
                $this->request->all()
            );
        }

        $client = $this->makeSoapClient();

        $parameters = $this->getSoapParameters($transaction);

        $result = $client->call('Get_new', $parameters, '', '', false, true);

        if (is_object($result) && $result->fault) {
            throw new RuntimeException(print_r($result));
        }

        if ($error = $client->getError()) {
            throw new RuntimeException($error);
        }

        $responseCode = $result['Get_newResult'];

        if ($responseCode == 3) {
            throw new ConnectionException($this, 'Hereglegchiin ner esvel nuuts ug buruu baina.');
        }

        return new CompleteProcessResponse(
            $this,
            $transaction,
            strlen($responseCode) == 6 ? Response::STATUS_APPROVED : Response::STATUS_DECLINED,
            $responseCode,
            $this->getSoapMessage($responseCode),
            $result
        );
    }

    /**
     * Get the key number for the merchant that taken from
     * the Golomt Bank
     *
     * @return string
     */
    public function getKeyNumber()
    {
        return $this->config->get('payways.gateways.golomt.key_number');
    }

    /**
     * @return array
     */
    public function getSupportedCurrencies()
    {
        return ['MNT'];
    }

    /**
     * Get the key number for the merchant that taken from
     * the Golomt Bank
     *
     * @return string
     */
    public function getSubId()
    {
        return $this->config->get('payways.gateways.golomt.sub_id');
    }

    public function getSoapUsername()
    {
        return $this->config->get('payways.gateways.golomt.soap_username');
    }

    public function getSoapPassword()
    {
        return $this->config->get('payways.gateways.golomt.soap_password');
    }

    /**
     * Make a new nusoap_client instance.
     *
     * @return nusoap_client
     * @throws SoupClientErrorException
     */
    private function makeSoapClient()
    {
        $client = new \nusoap_client("https://m.egolomt.mn:7073/persistence.asmx?WSDL", 'wsdl');

        if (! $client->getError()) {
            return $client;
        }

        throw new RuntimeException('Could not make a nusoap_client instance.');
    }

    /**
     * @param Transaction $transaction
     * @return array
     */
    private function getSoapParameters(Transaction $transaction)
    {
        return [
            'v0' => $this->getSoapUsername(),
            'v1' => $this->getSoapPassword(),
            'v2' => $transaction->getKey(),
            'v3' => $transaction->getDate()->format('Ymd'),
            'v4' => number_format($transaction->getAmount(), 2, '.', '')
        ];
    }

    /**
     * @param $responseCode
     * @return string
     */
    private function getSoapMessage($responseCode)
    {
        switch ($responseCode) {
            case 2: return 'Guilgee amjiltgui bolson baina.'; break;
            case 0: return 'Iim dugaar bolon guilgeenii duntei guilgee baazad burtgegdeegui baina.'; break;
            case 3: return 'Hereglegchiin ner esvel nuuts ug buruu baina.'; break;
        }
    }
}
