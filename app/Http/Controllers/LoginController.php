<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Requests\LoginRequest as RequestsLoginRequest;
use App\Services\LoginService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;


class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $loginRequest = App::make(RequestsLoginRequest::class);
        $loginRequest->email = $validated["email"];
        $loginRequest->password = $validated["password"];

        $service = App::make(LoginService::class);

        try {
            $login = $service->login($loginRequest);
            return response()
                ->json([
                    "message" => "Success login",
                    "data" => [
                        "username" => $login->user_id,
                        "role" => $login->role,
                        "access_token" => $login->accessToken
                    ]
                ], 200, [
                    "Content-Type" => "application/json"
                ]);
        } catch (Exception $error) {
            $errorMessages = strtolower($error->getMessage());
            $separateString = explode(" ", $errorMessages);
            $uniteString = implode(" ", array_slice($separateString, 1, count($separateString) - 1));
            return response()
                ->json([
                    "message" => [
                        $separateString[0] => [
                            $uniteString
                        ]
                    ],
                    "data" => null
                ], $error->getCode(), [
                    "Content-Type" => "application/json"
                ]);
        }
    }
}
