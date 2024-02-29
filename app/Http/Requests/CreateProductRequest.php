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
            "category" => "required",
            "sub_category_1" => "required",
            "sub_category_2" => "required",
            "sub_category_3" => "required",
            "name" => "required|max:70",
            "price" => "required",
            "description" => "required",
            "image_1" => "required|file|mimes:jpg,png,jpeg,webp|image|max:4096",
            "image_2" => "required|file||mimes:jpg,png,jpeg,webp|image|max:4096",
            "image_3" => "required|file|mimes:jpg,png,jpeg,webp|image|max:4096",
            "image_4" => "required|file|mimes:jpg,png,jpeg,webp|image|max:4096",
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
            "category.required" => "Please fill the category!",
            "sub_category_1.required" => "Please fill the category!",
            "sub_category_2.required" => "Please fill the category!",
            "sub_category_3.required" => "Please fill the category!",
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
