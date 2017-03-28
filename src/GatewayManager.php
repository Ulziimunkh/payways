<?php

namespace Selmonal\Payways;

use Illuminate\Support\Manager;
use ReflectionException;
use RuntimeException;

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
     *
     * @return Gateway
     */
    protected function createDriver($driver)
    {
        try {
            return $this->app->make('payways.'.$driver);
        } catch (ReflectionException $e) {
            throw new RuntimeException("Couldn't find gateway named ".$driver);
        }
    }
}
