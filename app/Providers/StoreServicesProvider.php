<?php

namespace App\Providers;

use App\Http\Controllers\StoreController;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class StoreServicesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(StoreController::class, function ($app) {
            return new StoreController($app->make(User::class));
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
