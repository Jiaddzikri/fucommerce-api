<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    private string $id = "";
    private string $username = "";
    private string $email = "";
    private string $password = "";
    private string $role = "=";


    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("delete from sessions");
        DB::delete("delete from users");

        $this->id =  uniqid();
        $this->username = "userExample123";
        $this->email = "example123@gmail.com";
        $this->password = "rahasia";
        $this->role = "customer";

        DB::table("users")
            ->insert([
                "id" => $this->id,
                "username" => $this->username,
                "email" => $this->email,
                "role" => $this->role,
                "password_hash" => Hash::make($this->password)
            ]);
    }

    public function testLoginSuccess(): void
    {
        $response = $this->post("/api/user/login", [
            "email" => $this->email,
            "password" => $this->password
        ]);

        $jwtToken = DB::table("sessions")
            ->where("user_id", "=", $this->id)
            ->get(["jwt_token"])
            ->first();

        $response->assertStatus(200)->assertJson([
            "message" => "Success login",
            "data" => [
                "username" => $this->username,
                "role" => $this->role,
                "token" => $jwtToken->jwt_token
            ]
        ])->assertHeader("Content-Type", "application/json");
    }

    public function testLoginEmptyEmail(): void
    {
        $this->post("/api/user/login", [
            "email" => "",
            "password" => $this->password
        ])->assertStatus(400)
            ->assertJson([
                "message" => [
                    "email" => ["Please fill the email!"]
                ],
                "data" => null
            ]);
    }
    public function testLoginEmptyPassword(): void
    {
        $this->post("/api/user/login", [
            "email" => "jiadsetiawan140604@gmail.com",
            "password" => ""
        ])->assertStatus(400)
            ->assertJson([
                "message" => [
                    "password" => ["Please fill the password!"]
                ],
                "data" => null
            ]);
    }

    public function testLoginBothEmpty(): void
    {
        $this->post("/api/user/login", [
            "email" => "",
            "password" => ""
        ])->assertStatus(400)
            ->assertJson([
                "message" => [
                    "email" => [
                        "Please fill the email!"
                    ],
                    "password" => [
                        "Please fill the password!"
                    ]
                ],
                "data" => null
            ]);
    }

    public function testLoginUserNotFound(): void
    {
        $this->post("/api/user/login", [
            "email" => "notfound@gmail.com",
            "password" => "rahasia"
        ])->assertStatus(401)
            ->assertJson([
                "message" => [
                    "user" => [
                        "maybe you forget something? try again!"
                    ]
                ],
                "data" => null
            ]);
    }
    public function testLoginWrongPassword(): void
    {
        $this->post("/api/user/login", [
            "email" => $this->email,
            "password" => "salah"
        ])->assertStatus(401)
            ->assertJson([
                "message" => [
                    "user" => [
                        "maybe you forget something? try again!"
                    ]
                ],
                "data" => null
            ]);
    }
}
