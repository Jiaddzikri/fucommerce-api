<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateSellerRequest extends FormRequest
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
            "phone_number" => "required",
            "store_name" => "required|max:60",
            "store_domain" => "required|max:24",
            "store_address" => "required"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "message" => $validator->getMessageBag(),
            "data" => null
        ], 400));
    }

    public function messages(): array
    {
        return [
            "phone_number.required" => "Please fill the phone number",
            "store_name.required" => "please fill the store name",
            "store_name.required" => "Store name is too long, max 60 character",
            "store_domain.required" => "Please fill the domain store",
            "store_domain.max" => "Store domain is too long, max 24 character",
            "store_address.required" => "Please fill the store address"
        ];
    }
}
