<?php

namespace Selmonal\Payways;

use Guzzle\Http\Client;
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

        $this->app->singleton('payways', function ($app) {

            // Once the authentication service has actually been requested by the developer
            // we will set a variable in the application indicating such. This helps us
            // know that we need to set any queued cookies in the after event later.
            $app['auth.loaded'] = true;

            return new GatewayManager($app);
        });
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
        $this->app->bind('payways.khan', function () {

            $gateway = new \Selmonal\Payways\Gateways\Khan\Gateway(new Client());

            $gateway->setUsername($this->app['config']->get('payways.gateways.khan.username'));
            $gateway->setPassword($this->app['config']->get('payways.gateways.khan.password'));
            $gateway->setReturnUrl($this->app['config']->get('payways.gateways.khan.returnUrl'));

            return $gateway;
        });
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
