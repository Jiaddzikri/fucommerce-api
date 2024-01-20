<?php

namespace Tests\Feature;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SessionControllerTest extends TestCase
{
    private const SECRET_KEY = "fbdshfidfy28933j289ed3en89y3frqohcfnebr7489rhejrerhe9rhfw";
    private $id = "";
    private $username = "";
    private $email = "";
    private $phoneNumber = "";
    private $role = "";
    private $password = "";
    private $token = "";
    private $province = "";
    private $regency = "";
    private $district = "";

    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("delete from user_addresses");
        DB::delete("delete from sessions");
        DB::delete("delete from users");

        $this->id = uniqid();
        $this->username = "jiaddzikri";
        $this->email = "jiadsetiawan1240604@gmail.com";
        $this->phoneNumber = "0895344357539";
        $this->role = "customer";
        $this->password = Hash::make("rahasia");
        $this->province = "JAWA BARAT";
        $this->regency = "KABUPATEN SUMEDANG";
        $this->district = "TANJUNGSARI";

        //jwt token 

        $payload = [
            "username" => $this->username,
            "role" => $this->role
        ];

        $this->token = JWT::encode($payload, SessionControllerTest::SECRET_KEY, "HS256");

        DB::table("users")
            ->insert([
                "id" => $this->id,
                "username" => $this->username,
                "email" => $this->email,
                "phone_number" => $this->phoneNumber,
                "role" => $this->role,
                "password_hash" => $this->password
            ]);

        DB::table("sessions")
            ->insert([
                "user_id" => $this->id,
                "session_key" => uniqid(),
                "jwt_token" => $this->token,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]);

        DB::table("user_addresses")
            ->insert([
                "id" => uniqid(),
                "user_id" => $this->id,
                "province" => $this->province,
                "regency" => $this->regency,
                "district" => $this->district
            ]);
    }

    public function testFindSessionSuccess(): void
    {
        $this->get("/api/user/session/" . $this->token)
            ->assertStatus(200)
            ->assertJson([
                "message" => "Success",
                "data" => [
                    "id" => $this->id,
                    "email" => $this->email,
                    "username" => $this->username,
                    "role" => $this->role,
                    "phone_number" => $this->phoneNumber,
                    "address" => [
                        "province" => $this->province,
                        "regency" => $this->regency,
                        "district" => $this->district
                    ]
                ]
            ])
            ->assertHeader("Content-Type", "application/json");
    }

    public function testFindSessionNotFound(): void
    {
        DB::delete("delete from sessions");

        $this->get("/api/user/session/" . $this->token)
            ->assertStatus(401)
            ->assertJson([
                "message" => "Unauthorize",
                "data" => null
            ])
            ->assertHeader("Content-Type", "application/json");
    }

    public function testFindSessionInvalidToken(): void
    {
        $payload = [
            "username" => $this->username,
            "role" => $this->role
        ];

        $token = JWT::encode($payload, "invalidtoken", "HS256");

        $this->get("/api/user/session/" . $token)
            ->assertStatus(400)
            ->assertJson([
                "message" => "Signature verification failed",
                "data" => null
            ])
            ->assertHeader(
                "Content-Type",
                "application/json"
            );
    }

    public function testFindSessionUserNotFound(): void
    {
        $payload = [
            "username" => "notfound",
            "role" => $this->role
        ];

        $token = JWT::encode($payload, SessionControllerTest::SECRET_KEY, "HS256");

        $this->get("/api/user/session/" . $token)
            ->assertStatus(401)
            ->assertJson([
                "message" => "Unauthorize",
                "data" => null
            ])
            ->assertHeader("Content-Type", "application/json");
    }

    public function testFindSessionInvalidLogicToken(): void
    {
        $token = JWT::encode([], SessionControllerTest::SECRET_KEY, "HS256");

        $this->get("/api/user/session/" . $token)
            ->assertStatus(400)
            ->assertJson([
                "message" => "Information is not complete",
                "data" => null
            ])
            ->assertHeader("Content-Type", "application/json");
    }

    public function testDeleteSessionSuccess(): void
    {
        $this->delete("/api/user/session/" . $this->token)
            ->assertStatus(200)
            ->assertJson([
                "message" => "Success delete session",
                "data" => null
            ])->assertHeader("Content-Type", "application/json");

        $session = DB::table("sessions")->where([
            "user_id" => $this->id
        ])->first();

        self::assertNull($session);
    }

    public function testDeleteSessionTokenInvalidTokenSignature(): void
    {
        $payload = [
            "username" => $this->username,
            "role" => $this->role
        ];

        $token = JWT::encode($payload, "invalidtoken", "HS256");
        $this->delete("/api/user/session/" . $token)
            ->assertStatus(400)
            ->assertJson([
                "message" => "Signature verification failed",
                "data" => null
            ])
            ->assertHeader(
                "Content-Type",
                "application/json"
            );
    }

    public function testDeleteSessionUserNotFound(): void
    {
        $payload = [
            "username" => "notfound",
            "role" => $this->role
        ];

        $token = JWT::encode($payload, SessionControllerTest::SECRET_KEY, "HS256");

        $this->delete("/api/user/session/" . $token)
            ->assertStatus(400)
            ->assertJson([
                "message" => "User not found",
                "data" => null
            ])
            ->assertHeader("Content-Type", "application/json");
    }

    public function testFindDeleteInvalidLogicToken(): void
    {
        $token = JWT::encode([], SessionControllerTest::SECRET_KEY, "HS256");

        $this->delete("/api/user/session/" . $token)
            ->assertStatus(400)
            ->assertJson([
                "message" => "Information is not complete",
                "data" => null
            ])
            ->assertHeader("Content-Type", "application/json");
    }
}
