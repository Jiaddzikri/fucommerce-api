<?php

namespace App\Http\Controllers;


use App\Services\SessionService;
use App\Services\SessionServiceImplementation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Exception;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function __construct(private SessionServiceImplementation $sessionService)
    {
    }
    public function find(Request $request): JsonResponse
    {
        $accessToken = $request->header("Authorization");
        try {
            $find = $this->sessionService->find($accessToken);

            return response()
                ->json([
                    "message" => "Success",
                    "data" => [
                        "user_id" => $find->user_id,
                        "session_id" => $find->session_id,
                        "username" => $find->username,
                        "role" => $find->role,
                        "expire_at" => $find->expires
                    ]
                ], 200, [
                    "Content-Type" => "application/json"
                ]);
        } catch (Exception $error) {
            return response()
                ->json([
                    "message" => [$error->getMessage()],
                    "data" => null
                ], $error->getCode(), [
                    "Content-Type" => "application/json"
                ]);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        $token = $request->header("Access-Token");
        $session = App::make(SessionService::class);

        try {
            $session->delete($token);

            return response()
                ->json([
                    "message" => "Success delete session",
                    "data" => null
                ], 200, [
                    "Content-Type" => "application/json"
                ]);
        } catch (Exception $error) {
            return response()
                ->json([
                    "message" => $error->getMessage(),
                    "data" => null
                ], $error->getCode(), [
                    "Content-Type" => "application/json"
                ]);
        }
    }
}
