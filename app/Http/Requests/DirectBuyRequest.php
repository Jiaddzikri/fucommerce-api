<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DirectBuyRequest extends FormRequest
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
            "product_id" => "required",
            "note" => "max:100",
            "quantity" => "required"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "message" => $validator->getMessageBag(),
            "data" => null
        ], 400));
    }

    public function messages()
    {
        return [
            "product_id.required" => "need an product id",
            "quantity.required" => "quantity must more than 0",
            "note.max" => "note must not more than 100 characters"
        ];
    }
}
