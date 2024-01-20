<?php

namespace App\Providers;

use App\Http\Controllers\SellerController;
use App\Models\User;
use App\Services\SessionService;
use Illuminate\Support\ServiceProvider;

class SellerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SellerController::class, function ($app) {
            return new SellerController($app->make(User::class), $app->make(SessionService::class));
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
