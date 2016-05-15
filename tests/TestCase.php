<?php

namespace Selmonal\Payways;

use Omnipay\Tests\TestCase as OmniTestCase;

class TestCase extends OmniTestCase
{
    protected function makeTransaction($id = 100, $amount = 7300, $currency = 'MNT')
    {
        return new Transaction(compact('id', 'amount', 'currency'));
    }
}