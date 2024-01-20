<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateProductRequest extends FormRequest
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
            "name" => "required|max:50",
            "price" => "required",
            "description" => "required",
            "image_1" => "required|mimes:jpg,png,jpeg,webp|image|max:4096",
            "image_2" => "required|mimes:jpg,png,jpeg,webp|image|max:4096",
            "image_3" => "required|mimes:jpg,png,jpeg,webp|image|max:4096",
            "image_4" => "required|mimes:jpg,png,jpeg,webp|image|max:4096",
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
            "category_id.required" => "Please fill the category!",
            "sub_category_1_id.required" => "Please fill the category!",
            "sub_category_2_id.required" => "Please fill the category!",
            "sub_category_3_id.required" => "Please fill the category!",
            "name.required" => "Please fill the name!",
            "price.required" => "Please fill the price!",
            "description.required" => "Please fill the description",
            "image_1.required" => "Please fill the image",
            "image_2.required" => "Please fill the image",
            "image_3.required" => "Please fill the image",
            "image_4.required" => "Please fill the image",
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
