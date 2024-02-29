<?php

namespace App\Providers;

use App\Http\Controllers\DiscussionController;
use App\Models\DiscussionReply;
use App\Models\ProductDiscussion;
use App\Services\SessionService;
use Illuminate\Support\ServiceProvider;

class DiscussionServiceProvider extends ServiceProvider
{
    public  $singletons = [
        ProductDiscussion::class => ProductDiscussion::class,
        DiscussionReply::class => DiscussionReply::class
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DiscussionController::class, function ($app) {
            return new DiscussionController($app->make(ProductDiscussion::class), $app->make(SessionService::class), $app->make(DiscussionReply::class));
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
