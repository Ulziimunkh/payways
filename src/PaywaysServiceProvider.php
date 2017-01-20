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
        $this->registerState();

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
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        // Publish config
        $this->publishes([
            __DIR__.'/../resources/config/config.php' => config_path('payways.php'),
        ], 'config');
    }

    /**
     * Register log gateway.
     *
     * @return void
     */
    private function registerLog()
    {
        $this->app->bind('payways.log', function () {
            $gateway = $this->app->make('Selmonal\Payways\Gateways\Log\Gateway');

            $gateway->setSupportedCurrencies(['mnt', 'usd']);

            return $gateway;
        });
    }

    /**
     * Register a gateway for the Khan Bank.
     *
     * @return void
     */
    private function registerKhan()
    {
        $this->app->bind('payways.khan', function () {
            $gateway = $this->app->make('Selmonal\Payways\Gateways\Khan\Gateway');

            $gateway->setUsername($this->app['config']->get('payways.gateways.khan.username'));
            $gateway->setPassword($this->app['config']->get('payways.gateways.khan.password'));
            $gateway->setReturnUrl($this->app['config']->get('payways.gateways.khan.returnUrl'));
            $gateway->setSupportedCurrencies(
                explode(',', $this->app['config']->get('payways.gateways.khan.currency'))
            );

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
        $this->app->bind('payways.golomt', function () {
            $gateway = $this->app->make('Selmonal\Payways\Gateways\Golomt\Gateway');

            $gateway->setKeyNumber($this->app['config']->get('payways.gateways.golomt.key_number'));
            $gateway->setSubId($this->app['config']->get('payways.gateways.golomt.sub_id'));
            $gateway->setSoapUsername($this->app['config']->get('payways.gateways.golomt.soap_username'));
            $gateway->setSoapPassword($this->app['config']->get('payways.gateways.golomt.soap_password'));
            $gateway->setSupportedCurrencies(
                explode(',', $this->app['config']->get('payways.gateways.golomt.currency'))
            );

            return $gateway;
        });
    }

    /**
     * Register a gateway for the State Bank.
     *
     * @return void
     */
    private function registerState()
    {
        $this->app->bind('payways.state', function () {

            $gateway = $this->app->make('Selmonal\Payways\Gateways\State\Gateway');

            $gateway->setSupportedCurrencies(
                explode(',', $this->app['config']->get('payways.gateways.state.currency'))
            );

            $gateway->setCallbackUrl(config('payways.gateways.state.returnUrl'));
            
            return $gateway;
        });
    }
}
