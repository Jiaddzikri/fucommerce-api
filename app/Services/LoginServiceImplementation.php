<?php

namespace App\Services;

use App\Models\User;
use App\Requests\CreateSessionRequest;
use App\Requests\LoginRequest;
use App\Response\LoginResponse;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class LoginServiceImplementation implements LoginService
{
    public function __construct(private SessionServiceImplementation $session, private User $userModel)
    {
    }

    public function login(LoginRequest $request): LoginResponse
    {
        if ($this->userModel::where("email", "=", $request->email)->exists()) {
            $userData = $this->userModel::where("email", "=", $request->email)->first();
            if (Hash::check($request->password, $userData->password_hash)) {
                $session = App::make(CreateSessionRequest::class);
                $session->user_id = $userData->id;
                $session->role = $userData->role;
                $create = $this->session->create($session);

                $response = App::make(LoginResponse::class);
                $response->user_id = $userData->id;
                $response->role = $userData->role;
                $response->accessToken = $create->accessToken;

                return $response;
            }
            throw new Exception("Password is wrong!", 400);
        }
        throw new Exception("User doesn't exists!", 401);
    }
}
