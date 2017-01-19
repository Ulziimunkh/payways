<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Selmonal\Payways\PaywaysFacade;
use Selmonal\Payways\Transaction;

class TestCase extends Orchestra\Testbench\TestCase
{

	public function setUp()
	{
		parent::setUp();

		$this->loadMigrationsFrom([
		    '--database' => 'testbench',
		    '--realpath' => realpath(__DIR__.'/../database/migrations'),
		]);
	}

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
	    // Setup default database to use sqlite :memory:
	    $app['config']->set('database.default', 'testbench');
	    $app['config']->set('database.connections.testbench', [
	        'driver'   => 'sqlite',
	        'database' => ':memory:',
	        'prefix'   => '',
	    ]);
	}

	/**
	 * Register the package service provider.
	 * 
	 * @param  Illuminate\Foundation\Application $app
	 * @return array
	 */
	protected function getPackageProviders($app)
	{
	    return [Selmonal\Payways\PaywaysServiceProvider::class];
	}

	/**
	 * Register the package aliases.
	 * 
	 * @param  Illuminate\Foundation\Application $app
	 * @return array
	 */
	protected function getPackageAliases($app)
	{
	    return [
	        'Payways' => PaywaysFacade::Class
	    ];
	}

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
