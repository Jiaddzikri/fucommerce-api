<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Requests\RegisterRequest as RequestsRegisterRequest;
use App\Services\RegisterService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RegisterController extends Controller
{
  public function register(RegisterRequest $request): JsonResponse
  {
    $validated = $request->validated();

    $request = App::make(RequestsRegisterRequest::class);
    $request->username = $validated["username"];
    $request->email =  $validated["email"];
    $request->password =  $validated["password"];


    $service = App::make(RegisterService::class);

    try {
      $register = $service->register($request);

      return response()->json([
        "message" => "Success Register",
        "data" => [
          "user_id" => $register->user_id,
          "role" => $register->role,
          "access_token" => $register->accessToken,
        ]
      ], 200, [
        "Content-Type" => "application/json"
      ]);
    } catch (Exception $error) {
      return response()->json([
        "message" => [
          strtolower(explode(" ", $error->getMessage())[0]) => [
            $error->getMessage()
          ]
        ],
        "data" => null
      ], 400, [
        "Content-Type" => "application/json"
      ]);
    }
  }
}
