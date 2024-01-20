<?php

namespace App\Providers;

use App\Http\Controllers\ProductController;
use App\Models\ProductImages;
use App\Models\Products;
use App\Services\SessionService;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public $singletons = [
        Products::class => Products::class,
        ProductImages::class => ProductImages::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProductController::class, function ($app) {
            return new ProductController($app->make(Products::class), $app->make(SessionService::class), $app->make(ProductImages::class));
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
