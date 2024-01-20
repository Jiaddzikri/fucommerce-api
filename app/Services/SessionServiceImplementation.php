<?php

namespace App\Services;

use App\Models\Session as ModelsSession;
use App\Models\User;
use App\Requests\CreateSessionRequest;
use App\Response\CreateSessionResponse;
use App\Response\FindSessionResponse;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\App;
use Exception;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use LogicException;
use UnexpectedValueException;

class SessionServiceImplementation implements SessionService
{
    private const SECRET_KEY = "fbdshfidfy28933j289ed3en89y3frqohcfnebr7489rhejrerdflgjodfgjdf8jvdfjvfdvdfjvdfjvjvfjvfdvfjvfjvfdvfvfnvoifjvodfjgajfdaopgjpgjasp[gjasfjgpsgjspdfgjposgjpsojgpsojfgsopgjfsdpgijhe9rhfw";

    public function __construct(private ModelsSession $sessionModel, private User $userModel)
    {
    }

    public function create(CreateSessionRequest $request): CreateSessionResponse
    {
        // validating create session requests
        if ($this->userModel::where("id", $request->user_id)->doesntExist()) {
            throw new Exception("User not found!", 400);
        }

        // if validated create token expire period
        $expireDate = time() + 3 * 60 * 60 * 24;
        $expireTimestamp = date("y-m-d h:m:s", $expireDate);
        $insert = $this->sessionModel;

        DB::transaction(function () use ($request, $expireTimestamp, $insert) {
            // insert requests to sessions database
            $insert->id = uniqid();
            $insert->user_id = $request->user_id;
            $insert->session_key = uniqid();
            $insert->access_token = $this->createAccessToken($request);
            $insert->expires = $expireTimestamp;
            $insert->save();
        });
        // return create session response;
        $response = App::make(CreateSessionResponse::class);
        $response->user_id = $request->user_id;
        $response->role = $request->role;
        $response->expires = $expireTimestamp;
        $response->accessToken = $insert->access_token;
        return $response;
    }

    public function find(?string $accessToken = null): FindSessionResponse
    {
        $validationAccessToken = $this->validateToken($accessToken);

        if ($this->sessionModel::where("user_id", $validationAccessToken["user_id"])->doesntExist()) {
            throw new Exception("User not found", 400);
        }

        $session = $this->sessionModel::join("users", function (JoinClause $join) use ($validationAccessToken) {
            $join->on("users.id", "=", "sessions.user_id")
                ->where("sessions.user_id", "=", $validationAccessToken["user_id"]);
        })->select("users.username", "users.role", "sessions.id", "sessions.user_id", "sessions.expires", "sessions.access_token")
            ->get()
            ->first();

        if ($session["role"] != $validationAccessToken["role"]) {
            throw new Exception("Unauthorize", 401);
        }

        $response = App::make(FindSessionResponse::class);
        $response->user_id = $session["user_id"];
        $response->session_id = $session["id"];
        $response->username = $session["username"];
        $response->role = $session["role"];
        $response->accessToken = $session["access_token"];
        $response->expires = $session["expires"];
        return $response;
    }

    public function delete(?string $accessToken = null): bool
    {
        $validationAccessToken = $this->validateToken($accessToken);

        $session = $this->sessionModel::where("user_id", $validationAccessToken["user_id"])->delete();

        if ($session > 0) {
            return true;
        }
        throw new Exception("Session Not Found", 400);
    }

    private function validateToken(?string $jwt = null): array|object
    {
        if (is_null($jwt))
            throw new Exception("Please provide a token", 400);

        try {
            $decoded = (array) JWT::decode($jwt, new Key(SessionServiceImplementation::SECRET_KEY, "HS256"));
        } catch (UnexpectedValueException $error) {
            throw new Exception($error->getMessage(), 400);
        } catch (LogicException $error) {
            throw new Exception($error->getMessage(), 400);
        }
        return !empty($decoded) ? $decoded : throw new Exception("Information is not complete", 400);
    }

    private function createAccessToken(CreateSessionRequest $request): string
    {
        return JWT::encode([
            "user_id" => $request->user_id,
            "role" => $request->role
        ], SessionServiceImplementation::SECRET_KEY, "HS256");
    }
}
