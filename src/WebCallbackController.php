<?php

namespace Selmonal\Payways;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Selmonal\Xml\Xml;

abstract class WebCallbackController extends Controller
{
    /**
     * Handle bank callback request.
     *
     * @param Request $request
     * @param string  $gateway
     *
     * @return \Illuminate\Http\Response
     */
    public function handleCallback(Request $request, $gateway)
    {
        $transaction = $this->getTransaction($request, $gateway);

        $response = $transaction->completeProcess($transaction);

        return $this->processed($transaction, $response);
    }

    /**
     * @return Transaction
     */
    protected function getTransaction(Request $request, $gateway)
    {
        if ($gateway === 'khan') {
            return Transaction::findByReference($request->get('orderId'), $gateway);
        } elseif ($gateway === 'golomt') {
            return Transaction::findOrFail($request->get('trans_number'));
        } elseif ($gateway === 'state') {
            return Transaction::findOrFail($request->get('trans_id'));
        } elseif ($gateway === 'tdb') {
            return Transaction::findOrFail($this->getTDBOrderId($request));
        }

        abort(404);
    }

    /**
     * Handle transaction response.
     *
     * @param Transaction $transaction
     * @param Response    $response
     *
     * @return \Illuminate\Http\Response
     */
    protected function processed($transaction, $response)
    {
        if ($response->isSuccessful()) {
            return $this->onSuccess($transaction, $response);
        }

        return $this->onFail($transaction, $response);
    }

    /**
     * Handle success transaction response.
     *
     * @param Transaction $transaction
     * @param Response    $response
     *
     * @return \Illuminate\Http\Response
     */
    abstract protected function onSuccess($transaction, $response);

    /**
     * Handle failed transaction response.
     *
     * @param Transaction $transaction
     * @param Response    $response
     *
     * @return \Illuminate\Http\Response
     */
    abstract protected function onFail($transaction, $response);

    /**
     * Get the tdb order id from the request.
     *
     * @param Request $request
     *
     * @return string
     */
    private function getTDBOrderId($request)
    {
        $xml = new Xml();
        $xml->loadFromString($request->get('xmlmsg'));

        return $xml->get('OrderID');
    }
}
