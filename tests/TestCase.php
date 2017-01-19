<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Selmonal\Payways\Transaction;

class TestCase extends Orchestra\Testbench\TestCase
{
	/**
	 * Make a new transaction instance.
	 * 
	 * @param  integer $id      
	 * @param  integer $amount  
	 * @param  string  $currency
	 * @return Transacation
	 */
    protected function makeTransaction($id = 100, $amount = 7300, $currency = 'MNT')
    {
        return new Transaction(compact('id', 'amount', 'currency'));
    }
}
