<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Support\Str;

class RegisterControllerTest extends TestCase
{
    private string $username;
    private string $email;
    private string $password;
    private const API_ENDPOINT = "/api/user/register";

    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("delete from sessions");
        DB::delete("delete from users");

        $this->username = "username14";
        $this->email = "useremail@gmail.com";
        $this->password = "secret";
    }

    public function testRegisterControllerSuccess(): void
    {
        $response = $this->post(RegisterControllerTest::API_ENDPOINT, [
            "username" => $this->username,
            "email" => $this->email,
            "password" => $this->password
        ]);


        $response->assertJson([
            "message" => "Success Register",
            "data" => [
                "username" => $response["data"]["username"],
                "role" => $response["data"]["role"],
                "token" => $response["data"]["token"]
            ]
        ])->assertStatus(200)->assertHeader("Content-Type", "application/json");
    }

    public function testRegisterBlankUsername(): void
    {
        $this->post(RegisterControllerTest::API_ENDPOINT, [
            "username" => "",
            "email" => $this->email,
            "password" => $this->password
        ])->assertStatus(400)->assertJson([
            "message" => [
                "username" => [
                    "Please fill the username!"
                ]
            ],
            "data" => null
        ])->assertHeader("Content-Type", "application/json");
    }

    public function testRegisterBlankEmail(): void
    {
        $this->post(RegisterControllerTest::API_ENDPOINT, [
            "username" => $this->username,
            "email" => "",
            "password" => $this->password
        ])->assertStatus(400)->assertJson([
            "message" => [
                "email" => [
                    "Please fill the email!"
                ]
            ],
            "data" => null
        ])->assertHeader("Content-Type", "application/json");
    }

    public function testRegisterBlankPassword(): void
    {
        $this->post(RegisterControllerTest::API_ENDPOINT, [
            "username" => $this->username,
            "email" => $this->email,
            "password" => ""
        ])->assertStatus(400)->assertJson([
            "message" => [
                "password" => [
                    "Please fill the password!"
                ]
            ],
            "data" => null
        ])->assertHeader("Content-Type", "application/json");
    }

    public function testRegisterPasswordTooShort(): void
    {
        $this->post(RegisterControllerTest::API_ENDPOINT, [
            "username" => $this->username,
            "email" => $this->email,
            "password" => "short"
        ])->assertStatus(400)->assertJson([
            "message" => [
                "password" => [
                    "Password is too short min 6 character at least!"
                ]
            ],
            "data" => null
        ])->assertHeader("Content-Type", "application/json");
    }

    public function testRegisterPasswordTooLong(): void
    {
        $this->post(RegisterControllerTest::API_ENDPOINT, [
            "username" => $this->username,
            "email" => $this->email,
            "password" => Str::random(85)
        ])->assertStatus(400)->assertJson([
            "message" => [
                "password" => [
                    "Password is too long max 80 character at least!"
                ]
            ],
            "data" => null
        ])->assertHeader("Content-Type", "application/json");
    }

    public function testRegisterNotValidEmail(): void
    {
        $this->post(RegisterControllerTest::API_ENDPOINT, [
            "username" => $this->username,
            "email" => "not valid email",
            "password" => $this->password
        ])->assertStatus(400)->assertJson([
            "message" => [
                "email" => [
                    "Please submit a valid email!"
                ]
            ]
        ])->assertHeader("Content-Type", "application/json");
    }

    public function testUsernameAlreadyExist(): void
    {
        $this->post(RegisterControllerTest::API_ENDPOINT, [
            "username" => $this->username,
            "email" => $this->email,
            "password" => $this->password
        ]);

        $this->post(RegisterControllerTest::API_ENDPOINT, [
            "username" => $this->username,
            "email" => $this->email,
            "password" => $this->password
        ])->assertStatus(400)->assertJson([
            "message" => [
                "username" => [
                    "Username already exist"
                ]
            ],
            "data" => null
        ])->assertHeader("Content-Type", "application/json");
    }

    public function testRegisterEmailAlreadyExist(): void
    {
        $this->post(RegisterControllerTest::API_ENDPOINT, [
            "username" => $this->username,
            "email" => $this->email,
            "password" => $this->password
        ]);

        $this->post(RegisterControllerTest::API_ENDPOINT, [
            "username" => "differentusername",
            "email" => $this->email,
            "password" => $this->password
        ])->assertStatus(400)->assertJson([
            "message" => [
                "email" => [
                    "Email already exist"
                ]
            ],
            "data" => null
        ])->assertHeader("Content-Type", "application/json");
    }
}
