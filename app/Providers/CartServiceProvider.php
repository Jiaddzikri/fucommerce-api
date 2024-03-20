<?php

namespace App\Providers;

use App\Http\Controllers\CartController;
use App\Models\CartModel;
use App\Services\SessionService;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    public $singletons = [
        CartModel::class => CartModel::class
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CartController::class, function ($app) {
            return new CartController($app->make(SessionService::class), $app->make(CartModel::class));
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
