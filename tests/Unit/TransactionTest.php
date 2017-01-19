<?php

use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;

class TransactionTest extends TestCase
{
    /** @test */
    public function it_should_with_be_by_default()
    {
    	$transaction = new Transaction;

    	$this->assertEquals(Response::STATUS_PENDING, $transaction->response_status);
    }
}