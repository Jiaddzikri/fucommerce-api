<?php

namespace App\Providers;

use App\Http\Controllers\UserController;
use App\Models\User;
use App\Requests\LoginRequest;
use App\Requests\RegisterRequest;
use App\Response\LoginResponse;
use App\Response\RegisterResponse;
use App\Response\UserResponse;
use App\Services\LoginServiceImplementation;
use App\Services\LoginService;
use App\Services\RegisterService;
use App\Services\RegisterServiceImplementation;
use App\Services\SessionService;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        User::class => User::class,
        RegisterRequest::class => RegisterRequest::class,
        RegisterResponse::class => RegisterResponse::class,
        LoginRequest::class => LoginRequest::class,
        LoginResponse::class => LoginResponse::class,
        UserResponse::class => UserResponse::class,

    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RegisterService::class, function ($app) {
            return new RegisterServiceImplementation($app->make(SessionService::class), $app->make(User::class));
        });

        $this->app->singleton(LoginService::class, function ($app) {
            return new LoginServiceImplementation($app->make(SessionService::class), $app->make(User::class));
        });

        $this->app->singleton(UserController::class, function ($app) {
            return new UserController($app->make(SessionService::class), $app->make(User::class));
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
