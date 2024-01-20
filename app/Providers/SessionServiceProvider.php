<?php

namespace App\Providers;

use App\Http\Controllers\SessionController;
use App\Models\Session as ModelsSession;
use App\Models\User;
use App\Requests\CreateSessionRequest;
use App\Response\CreateSessionResponse;
use App\Response\FindSessionResponse;
use App\Services\SessionService;
use App\Services\SessionServiceImplementation;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        ModelsSession::class => ModelsSession::class,
        CreateSessionRequest::class => CreateSessionRequest::class,
        CreateSessionResponse::class => CreateSessionResponse::class,
        FindSessionResponse::class => FindSessionResponse::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SessionService::class, function ($app) {
            return new SessionServiceImplementation($app->make(ModelsSession::class), $app->make(User::class));
        });

        $this->app->singleton(SessionController::class, function ($app) {
            return new SessionController($app->make(SessionService::class));
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

    public function provides()
    {
        return [SessionService::class, ModelsSession::class];
    }
}
