<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "username" => "bail|required|max:20",
            "email" => "required|email|max:40",
            "password" => "required|min:6|max:80"
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response([
            "message" => $validator->getMessageBag(),
            "data" => null
        ], 400));
    }

    public function messages(): array
    {
        return [
            "username.required" => "Please fill the username!",
            "username.unique" => "Username must unique!",
            "email.required" => "Please fill the email!",
            "email.email" => "Please submit a valid email!",
            "password.required" => "Please fill the password!",
            "password.min" => "Password is too short min 6 character at least!",
            "password.max" => "Password is too long max 80 character at least!"
        ];
    }
}
