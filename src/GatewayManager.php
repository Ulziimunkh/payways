<?php

namespace Selmonal\Payways;


use Illuminate\Support\Manager;

class GatewayManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['payways.default'];
    }

    /**
     * @param string $driver
     * @return Gateway
     */
    protected function createDriver($driver)
    {
        return $this->app->make('payways.'. $driver);
    }
}