<?php

namespace App\Services;

use App\Models\User;
use App\Requests\CreateSessionRequest;
use App\Requests\RegisterRequest;
use App\Response\CreateSessionResponse;
use App\Response\RegisterResponse;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterServiceImplementation implements RegisterService
{
    public function __construct(private SessionServiceImplementation $session, private User $userModel)
    {
    }

    private function validationRegister(RegisterRequest $request): void
    {
        if ($this->userModel::where("username", '=', $request->username)->exists()) {
            throw new Exception("Username already exist", 400);
        } else if ($this->userModel::where("email", '=', $request->email)->exists()) {
            throw new Exception("Email already exist", 400);
        }
    }


    public function register(RegisterRequest $request): RegisterResponse
    {
        $this->validationRegister($request);

        DB::transaction(function () use ($request) {
            $this->userModel->id = uniqid();
            $this->userModel->username = $request->username;
            $this->userModel->email = $request->email;
            $this->userModel->password_hash = Hash::make($request->password);
            $this->userModel->role = "customer";
            $this->userModel->save();
        });

        $sessionRequest = App::make(CreateSessionRequest::class);
        $sessionRequest->user_id = $this->userModel->id;
        $sessionRequest->role = $this->userModel->role;

        $session = $this->session->create($sessionRequest);

        $response = App::make(RegisterResponse::class);
        $response->user_id = $session->user_id;
        $response->role = $session->role;
        $response->accessToken = $session->accessToken;

        return $response;
    }
}
