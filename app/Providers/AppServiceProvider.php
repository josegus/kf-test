<?php

namespace App\Providers;

use App\Actions\Stripe\RefundCharge;
use App\Actions\Stripe\RefundChargeInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind stripe charge interface to container. This will be overwriten on tests
        $this->app->instance(RefundChargeInterface::class, new RefundCharge);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
