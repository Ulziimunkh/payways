<?php

use Carbon\Carbon;
use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;

class TransactionTest extends TestCase
{
    /** @test */
    public function it_should_with_be_by_default()
    {
        $transaction = new Transaction();

        $this->assertEquals(Response::STATUS_PENDING, $transaction->response_status);
    }

    /** @test */
    public function transaction_with_paid_at_date_are_paid()
    {
        $transaction = new Transaction();

        $transaction->paid_at = Carbon::now();

        $this->assertTrue($transaction->is_paid);
    }
}
