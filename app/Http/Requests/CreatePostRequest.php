<?php

namespace App\Http\Requests;

use App\Rules\ExistUser;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
            'user_id' => ['required', 'numeric', new ExistUser],
            'image' => ['nullable', 'mimes:png,jpg,jpeg,webp'],
        ];
    }
}
