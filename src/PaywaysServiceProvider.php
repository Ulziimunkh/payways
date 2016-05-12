<?php

namespace Selmonal\Payways;


use Illuminate\Support\ServiceProvider;

class PaywaysServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishes();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLog();
        $this->registerKhan();
        $this->registerGolomt();
    }

    /**
     * Register publishes.
     *
     * @return void
     */
    private function registerPublishes()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        // Publish config
        $this->publishes([
            __DIR__.'/../resources/config/config.php' => config_path('payways.php'),
        ], 'config');
    }

    /**
     * Register log gateway
     *
     * @return void
     */
    private function registerLog()
    {
        $this->app->bind('payways.log', 'Selmonal\Payways\Gateways\Log\Gateway');
    }

    /**
     * Register a gateway for the Khan Bank.
     *
     * @return void
     */
    private function registerKhan()
    {
        $this->app->bind('payways.khan', 'Selmonal\Payways\Gateways\Khan\Gateway');
    }

    /**
     * Register a gateway for the Golomt Bank.
     *
     * @return void
     */
    private function registerGolomt()
    {
        $this->app->bind('payways.golomt', 'Selmonal\Payways\Gateways\Golomt\Gateway');
    }
}