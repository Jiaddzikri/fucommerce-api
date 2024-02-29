<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SessionServiceImplementation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{
    public function __construct(private SessionServiceImplementation $session, private User $user)
    {
    }

    public function edit(int $id, Request $request): JsonResponse
    {
        try {
            $this->session->find(explode(" ", $request->header("Authorization")[1]));

            $this->user::where("id", "=", $id)
                ->update([
                    "username" => $request->post("username"),
                    "email" => $request->post("email"),
                    "phone_number" => $request->post("phone_number"),
                ]);
            return response()->json([
                "message" => "Update Successfull",
                "data" => [
                    "username" => $request->post("username"),
                    "email" => $request->post("email"),
                    "phone_number" => $request->post("phone_number"),
                ]
            ], 201, [
                "Content-Type" => "application/json"
            ]);
        } catch (Exception $error) {
            return response()->json([
                "message" => $error->getMessage()
            ], $error->getCode(), [
                "Content-Type" => "application/json"
            ]);
        }
    }
}
