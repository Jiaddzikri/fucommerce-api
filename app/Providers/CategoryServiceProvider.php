<?php

namespace App\Providers;

use App\Http\Controllers\CategoryController;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{
    public $singletons = [
        Category::class => Category::class
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CategoryController::class, function ($app) {
            return new CategoryController($app->make(Category::class));
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
