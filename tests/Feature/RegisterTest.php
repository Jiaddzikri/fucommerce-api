<?php

namespace Tests\Feature;

use App\Requests\RegisterRequest;
use App\Response\RegisterResponse;
use App\Services\RegisterService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    private string $username;
    private string $email;
    private string $password;

    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from sessions");
        DB::delete("delete from user_addresses");
        DB::delete("delete from users");

        $this->username = "Jiad Dzikri Ramadia";
        $this->email = "jiadsetiawan140604@gmail.com";
        $this->password = "rahasia";
    }

    public function testRegisterSuccess(): RegisterResponse
    {
        $request = $this->app->make(RegisterRequest::class);
        $request->username = $this->username;
        $request->email = $this->email;
        $request->password = $this->password;

        $register = $this->app->make(RegisterService::class);
        $response = $register->register($request);

        $user = DB::table("users")->where("id", $response->user_id)->first();

        self::assertSame($user->username, $request->username);
        self::assertSame($user->email, $request->email);
        self::assertTrue(Hash::check($request->password, $user->password_hash));


        return $response;
    }

    public function testRegisterUsernameAlreadyExist()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage("Username already exist");

        $this->testRegisterSuccess();

        $request = $this->app->make(RegisterRequest::class);
        $request->username = $this->username;
        $request->email = "wawawa140604@gmail.com";
        $request->password = $this->password;

        $register = $this->app->make(RegisterService::class);
        $register->register($request);
    }

    public function testRegisterEmailAlreadyExist(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage("Email already exist");

        $this->testRegisterSuccess();

        $request = $this->app->make(RegisterRequest::class);
        $request->username = "alreadExist";
        $request->email = $this->email;
        $request->password = $this->password;

        $register = $this->app->make(RegisterService::class);
        $register->register($request);
    }
}
