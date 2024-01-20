<?php

namespace Tests\Feature;

use App\Domains\Session;
use App\Models\Seller;
use App\Models\Session as ModelsSession;
use App\Models\User;
use App\Requests\SellerRequest;
use App\Services\SellerService;
use App\Services\SessionService;
use App\Services\SessionServiceImplementation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SellerServiceTest extends TestCase
{
    private ?string $id = null;
    private ?string $username = null;
    private ?string $email = null;
    private ?string $phone_number = null;
    private ?string $role = null;
    private ?string $password = null;
    private ?string $token = null;
    private SessionServiceImplementation $sessionService;

    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("delete from sessions");
        DB::delete("delete from sellers");
        DB::delete("delete from users");

        $this->id = uniqid();
        $this->username = "Allen Rashford";
        $this->email = "allen@gmail.com";
        $this->phone_number = "879304732";
        $this->role = "customer";
        $this->password = Hash::make("rahasia");

        $user = $this->app->make(User::class);
        $user->id = $this->id;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->phone_number = $this->phone_number;
        $user->role = $this->role;
        $user->password_hash = $this->password;
        $user->save();

        $this->sessionService = $this->app->make(SessionService::class);

        $session = $this->app->make(Session::class);
        $session->username = $this->username;
        $session->role = $this->role;
        $session->sessionKey = uniqid();

        $create = $this->sessionService->create($session);
        $this->token = $create->token;
    }
    public function testSuccessSchemeRegistForSellerAccount()
    {
        $sellerService = $this->app->make(SellerService::class);
        $request = $this->app->make(SellerRequest::class);

        $request->id = uniqid();
        $request->user_id = $this->id;
        $request->storeName = "allenStore";
        $request->storeDomain = "allenStore";
        $request->storePhoneNumber = "3482573495";
        $request->storeDescription = "test description";
        $request->storeAddress = "test address";
        $request->token = $this->token;

        $response = $sellerService->create($request);

        // $sellerModel = $this->app->make(Seller::class);
        // $sellerModelData = $sellerModel::where("id", $response->id)->exists();
        // $sessionModel = $this->app->make(ModelsSession::class);
        // $sessionModelData = $sessionModel::where("jwt_token", $response->token)->exists();

        // self::assertTrue($sellerModelData);
        // self::assertTrue($sessionModelData);
    }
}
