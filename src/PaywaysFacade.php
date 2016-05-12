<?php

namespace Selmonal\Payways;

use Illuminate\Support\Facades\Facade;

class PaywaysFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'payways';
    }
}
