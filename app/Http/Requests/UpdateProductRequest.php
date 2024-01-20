<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
            "name" => "",
            "price" => "",
            "description" => "",
            "image_1" => "mimes:jpg,png,jpeg,webp|image|max:4096",
            "image_2" => "mimes:jpg,png,jpeg,webp|image|max:4096",
            "image_3" => "mimes:jpg,png,jpeg,webp|image|max:4096",
            "image_4" => "mimes:jpg,png,jpeg,webp|image|max:4096",
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
            "image_1.mimes" => "Please fill only an image, allowed extension [jpg, png, jpeg, webp]",
            "image_2.mimes" => "Please fill only an image, allowed extension [jpg, png, jpeg, webp]",
            "image_3.mimes" => "Please fill only an image, allowed extension [jpg, png, jpeg, webp]",
            "image_4.mimes" => "Please fill only an image, allowed extension [jpg, png, jpeg, webp]",
            "image_1.max" => "Your image size is too big, max 4MB",
            "image_2.max" => "Your image size is too big, max 4MB",
            "image_3.max" => "Your image size is too big, max 4MB",
            "image_4.max" => "Your image size is too big, max 4MB",
        ];
    }
}
