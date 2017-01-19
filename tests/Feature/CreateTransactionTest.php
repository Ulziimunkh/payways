<?php

class CreateTransactionTest extends TestCase
{
    /** @test */
    public function can_create_a_transaction()
    {
    	$transaction = Payways::driver('log')->transaction([
    		'amount' => 3000,
    		'currency' => 'mnt'
    	])->create();

    	$this->assertNotNull($transaction->fresh());
    	$this->assertEquals(3000, $transaction->amount);
    	$this->assertEquals('MNT', $transaction->currency);
    }
}