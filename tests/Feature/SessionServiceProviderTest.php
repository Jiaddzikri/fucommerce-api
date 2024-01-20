<?php

namespace Tests\Feature;

use App\Domains\Session;
use App\Models\Customer;
use App\Models\Session as ModelsSession;
use App\Response\SessionResponse;
use App\Services\SessionService;
use Tests\TestCase;

class SessionServiceProviderTest extends TestCase
{
    public function testSessionDomainsSingleton(): void
    {
        $domains1 = $this->app->make(Session::class);
        $domains2 = $this->app->make(Session::class);

        self::assertSame($domains1, $domains2);
    }

    public function testSessionModelsSingleton(): void 
    {
        $model1 = $this->app->make(ModelsSession::class);
        $model2 = $this->app->make(ModelsSession::class);

        self::assertSame($model1, $model2);
    }

    public function testSessioResponseSingleton(): void 
    {
        $response1 = $this->app->make(SessionResponse::class);
        $response2 = $this->app->make(SessionResponse::class);

        self::assertSame($response1, $response2);
    }

    public function testCustomerSingleton(): void 
    {
        $customer1 = $this->app->make(Customer::class);
        $customer2 = $this->app->make(Customer::class);

        self::assertSame($customer1, $customer2);
    }

    public function testSessionServiceSingleton(): void
    {
        $service1 = $this->app->make(SessionService::class);
        $service2 = $this->app->make(SessionService::class);

        self::assertSame($service1, $service2);
    }
}
