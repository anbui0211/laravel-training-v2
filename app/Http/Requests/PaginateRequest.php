<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'page' => $this->input('page') === null ? config('paginate.page') : (int)$this->input('page'),
            'limit' => $this->input('limit') === null ? config('paginate.limit') : (int) $this->input('limit'),
            'sort' => $this->input('sort') === null ? config('paginate.sort_column') :  $this->input('sort'),
            'direction' => $this->input('direction') === null ? config('paginate.sort_direction') : $this->input('direction'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1'],
            'sort' => ['nullable', 'string'],
            'direction' => ['nullable', 'string'],
        ];
    }
}
