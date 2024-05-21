<?php

namespace App\Providers;

use App\Http\Controllers\OrderController;
use App\Models\DirectBuy;
use App\Services\SessionService;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public $singletons = [
        DirectBuy::class => DirectBuy::class
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OrderController::class, function ($app) {
            return new OrderController($app->make(SessionService::class), $app->make(DirectBuy::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
