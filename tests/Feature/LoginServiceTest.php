<?php

namespace Tests\Feature;

use App\Requests\LoginRequest;
use App\Services\LoginService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginServiceTest extends TestCase
{
    private string $id;
    private string $username;
    private string $email;
    private string $phoneNumber;
    private string $password;
    private string $role;

    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("delete from sessions");
        DB::delete("delete from users");

        $this->id = uniqid();
        $this->username = "jiaddzikri14";
        $this->email = "jiaddzikri140604@gmail.com";
        $this->phoneNumber = "0895344357539";
        $this->password = Hash::make("rahasia");
        $this->role = "customer";

        DB::table("users")
            ->insert([
                "id" => $this->id,
                "username" => $this->username,
                "email" => $this->email,
                "phone_number" => $this->phoneNumber,
                "password_hash" => $this->password,
                "role" => $this->role
            ]);
    }

    public function testLoginSuccess(): void
    {
        $request = $this->app->make(LoginRequest::class);
        $request->email = $this->email;
        $request->password = "rahasia";

        $login = $this->app->make(LoginService::class);
        $loginResponse = $login->login($request);

        $sessions = DB::table("sessions")
            ->where("user_id", $this->id)
            ->first();

        self::assertSame($loginResponse->user_id, $this->id);
        self::assertSame($loginResponse->role, $this->role);
        self::assertSame($loginResponse->accessToken, $sessions->access_token);
    }

    public function testLoginUserNotFound(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(401);
        $this->expectExceptionMessage("User doesn't exists");

        $request = $this->app->make(LoginRequest::class);
        $request->email = "not found";
        $request->password = "rahasia";

        $login = $this->app->make(LoginService::class);
        $login->login($request);
    }

    public function testLoginIncorrectPassword(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage("Password is wrong!");

        $request = $this->app->make(LoginRequest::class);
        $request->email = $this->email;
        $request->password = "wrong password";

        $login = $this->app->make(LoginService::class);
        $login->login($request);
    }
}
