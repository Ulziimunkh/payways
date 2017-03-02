<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Selmonal\Payways\Response;
use Selmonal\Payways\Transaction;

class TransactionTest extends TestCase
{
    /** @test */
    public function it_should_record_the_currently_authenticated_user_as_transactioner()
    {
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Config::set('payways.user.model', UserStub::class);

        $user = UserStub::create(['name' => 'selmonal']);

        Auth::login($user);

        $transaction = Transaction::create([
            'amount'      => 3000,
            'currency'    => 'mnt',
            'description' => 'Test description.',
            'gateway'     => 'log',
        ]);

        $this->assertEquals(Auth::user()->id, $transaction->fresh()->user_id);
        $this->assertEquals(Auth::user()->name, $transaction->fresh()->user->name);

        Schema::drop('users');
    }

    /** @test */
    public function it_should_with_be_by_default()
    {
        $transaction = new Transaction();

        $this->assertEquals(Response::STATUS_PENDING, $transaction->response_status);
    }

    /** @test */
    public function transaction_with_paid_at_date_are_paid()
    {
        $transaction = new Transaction();

        $transaction->paid_at = Carbon::now();

        $this->assertTrue($transaction->is_paid);
    }
}

class UserStub extends User
{
    protected $table = 'users';
    protected $guarded = [];
}
