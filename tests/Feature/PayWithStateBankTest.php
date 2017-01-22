<?php

use Selmonal\Payways\Gateways\State\FakeHttpClient;
use Selmonal\Payways\Gateways\State\HttpClient;
use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;

class PayWithStateBankTest extends TestCase
{
    /** @test */
    public function can_pay_with_state_bank()
    {
        // Make a success client.
        $client = new FakeHttpClient();
        $client->setStatus('00');
        $client->setOrderId('123');
        $client->setOrderStatus('APPROVED');
        $client->setSessionId('456');
        $this->app->instance(HttpClient::class, $client);

        // Create a transaction.
        $transaction = Payways::driver('state')
            ->transaction(['amount' => 100, 'currency' => 'mnt'])
            ->create();

        // Processing the transaction.
        $response = $transaction->process();
        $this->assertTrue($response->isRedirect());
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(Response::STATUS_PENDING, $transaction->fresh()->response_status);
        $this->assertEquals(json_encode(['orderId' => '123', 'sessionId' => '456']), $transaction->fresh()->reference);

        // Complete the transcation
        $response = $transaction->completeProcess();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals(Response::STATUS_APPROVED, $transaction->fresh()->response_status);
        $this->assertTrue($transaction->fresh()->is_paid);
    }
}
