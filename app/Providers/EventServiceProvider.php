<?php

namespace App\Providers;

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
        \App\Events\CoopCreating::class => [
            \App\Listeners\SetCoopStatus::class,
        ],
        \App\Events\CoopCanceled::class => [
            \App\Listeners\CancelCoopPurchasesListener::class,
            \App\Listeners\NotifyOwnerCoopCanceledListener::class,
        ],
        \App\Events\PurchaseCreated::class => [
            \App\Listeners\CreatePurchaseTransaction::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
