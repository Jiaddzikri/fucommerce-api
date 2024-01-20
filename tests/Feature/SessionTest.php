<?php

namespace Tests\Feature;

use App\Domains\Session as DomainsSession;
use App\Models\Session;
use App\Requests\CreateSessionRequest;
use App\Services\SessionService;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SessionTest extends TestCase
{
   private const SECRET_KEY = "fbdshfidfy28933j289ed3en89y3frqohcfnebr7489rhejrerdflgjodfgjdf8jvdfjvfdvdfjvdfjvjvfjvfdvfjvfjvfdvfvfnvoifjvodfjgajfdaopgjpgjasp[gjasfjgpsgjspdfgjposgjpsojgpsojfgsopgjfsdpgijhe9rhfw";
   private string $id = "";
   private string $username = "";
   private string $email = "";
   private string $phoneNumber = "";
   private string $role = "";
   private string $password = "";

   protected function setUp(): void
   {
      parent::setUp();

      DB::delete("delete from sessions");
      DB::delete("delete from user_addresses");
      DB::delete("delete from users");

      $this->id = uniqid();
      $this->username = "Jiad Dzikri Ramadia";
      $this->email = "jiadsetiawan140604@gmail.com";
      $this->role = "customer";
      $this->password = Hash::make("rahasia");
      $this->phoneNumber = "0895344357539";

      DB::table("users")
         ->insert([
            "id" => $this->id,
            "username" => $this->username,
            "email" => $this->email,
            "phone_number" => $this->phoneNumber,
            "role" => $this->role,
            "password_hash" => $this->password
         ]);
   }

   public function testCreateSessionSuccess(): string
   {
      $sessionRequest = $this->app->make(CreateSessionRequest::class);
      $sessionRequest->user_id = $this->id;
      $sessionRequest->role = $this->role;

      $create = $this->app->make(SessionService::class);
      $response = $create->create($sessionRequest);

      $session = Session::rightJoin("users", "sessions.user_id", "=", "users.id")
         ->where("user_id", $this->id)
         ->first();

      self::assertSame($response->user_id, $session->id);
      self::assertSame($response->role, $session->role);
      self::assertSame($response->accessToken, $session->access_token);

      return $session->access_token;
   }

   public function testFindSessionSuccess(): void
   {
      $token = $this->testCreateSessionSuccess();

      $session = $this->app->make(SessionService::class);
      $response = $session->find($token);

      self::assertSame($response->accessToken, $token);
      self::assertSame($response->username, $this->username);
      self::assertSame($response->user_id, $this->id);
      self::assertSame($response->role, $this->role);
   }

   public function testFindSessionInvalidToken(): void
   {
      $this->expectException(Exception::class);
      $token = "invalid Token";

      $session = $this->app->make(SessionService::class);
      $session->find($token);
   }

   public function testFindSessionNotFound(): void
   {
      $this->expectException(Exception::class);
      $this->expectExceptionMessage("User not found");
      $this->expectExceptionCode(400);

      $this->testCreateSessionSuccess();

      //from jwt debugger with correct signature, but incorrect user
      $invalidToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoic2FsYWgiLCJyb2xlIjoiY3VzdG9tZXIifQ.eS0bc_Gwk6wVsp_Z5Z4K7wBR176kM1uuYjYroFa4CGw";

      $find = $this->app->make(SessionService::class);
      $find->find($invalidToken);
   }

   public function testFindSessionWithIncorrectRole(): void
   {
      $this->expectException(Exception::class);
      $this->expectExceptionMessage("Unauthorize");
      $this->expectExceptionCode(401);

      $this->testCreateSessionSuccess();

      // invalid token with correct signature, correct username, incorrect role
      $token = JWT::encode([
         "user_id" => $this->id,
         "role" => "admin"
      ], SessionTest::SECRET_KEY, "HS256");

      $find = $this->app->make(SessionService::class);
      $find->find($token);
   }

   public function testFindSessionUserIdNotFound(): void
   {
      $this->expectException(Exception::class);
      $this->expectExceptionMessage("User not found");
      $this->expectExceptionCode(400);

      $token = $this->testCreateSessionSuccess();

      $session = $this->app->make(SessionService::class);
      DB::table("sessions")->where("user_id", $this->id)->delete();

      $session->find($token);
   }

   public function testFindSessionSessionKeyNotFound(): void
   {
      $this->expectException(Exception::class);
      $this->expectExceptionMessage("User not found");
      $this->expectExceptionCode(400);

      $token = $this->testCreateSessionSuccess();

      $session = $this->app->make(SessionService::class);
      $sessionKey = DB::table("sessions")->where("user_id", $this->id)->first();

      DB::table("sessions")->where("session_key", $sessionKey->session_key)->delete();

      $session->find($token);
   }

   public function testDeleteSessionSuccess(): void
   {
      $token = $this->testCreateSessionSuccess();

      $sessionService = $this->app->make(SessionService::class);
      $delete = $sessionService->delete($token);

      self::assertTrue($delete);
   }
}
