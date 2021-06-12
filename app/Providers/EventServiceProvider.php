<?php

namespace App\Providers;

use App\Listeners\AccountStatementListener;
use App\Listeners\AdminListener;
use App\Listeners\TalkToUsMessageListener;
use App\Listeners\TransactionListener;
use App\Listeners\TransactionRequestListener;
use App\Listeners\UserCreatedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\UserCreated' => [
            'App\Listeners\UserCreatedListener',
        ],
        'App\Events\AirtimeTransactionEvent' => [
            'App\Listeners\AdminListener',
            'App\Listeners\TransactionListener',
        ],
        'App\Events\CableTransactionEvent' => [
            'App\Listeners\AdminListener',
            'App\Listeners\TransactionListener',
        ],
        'App\Events\DataTransactionEvent' => [
            'App\Listeners\AdminListener',
            'App\Listeners\TransactionListener',
        ],
        'App\Events\ElectricityTransactionEvent' => [
            'App\Listeners\AdminListener',
            'App\Listeners\TransactionListener',
        ],
        'App\Events\WalletTransactionEvent' => [
            'App\Listeners\AdminListener',
            'App\Listeners\TransactionListener',
        ],
        'App\Events\TalkToUsMessageEvent' => [
            'App\Listeners\TalkToUsMessageListener',
        ],

        'App\Events\AccountStatementEvent' => [
            'App\Listeners\AccountStatementListener',
        ],

    ];

    protected $subscribe = [
        AdminListener::class,
        TransactionListener::class,
        UserCreatedListener::class,
        TalkToUsMessageListener::class,
        AccountStatementListener::class

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
