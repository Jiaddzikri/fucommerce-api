<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class WriteProductDiscussionReplyRequest extends FormRequest
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
            "discussion_id" => "required",
            "receiver_id" => "required",
            "content" => "required"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "message" => $validator->getMessageBag(),
            "data" => null
        ], 400));
    }

    public function messages()
    {
        return [
            "product_id.required" => "Need Product Id",
            "discussion_id.required" => "Need Discussion Id",
            "receiver_id.required" => "Need receiver id",
            "content.required" => "Message cannot empty!"
        ];
    }
}
