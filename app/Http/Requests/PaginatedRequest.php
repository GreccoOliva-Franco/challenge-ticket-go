<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginatedRequest extends FormRequest
{
    private const PAGINATION_KEYS = ['page', 'per_page'];

    public function prepareForValidation(): void
    {
        $this->merge([
            'page' => $this['page'] ? intval($this['page']) : 1,
            'per_page' => $this['per_page'] ? intval($this['per_page']) : 15,
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
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1',
        ];
    }

    public function getFilter(array $input): array
    {
        return array_reduce($input, function () {}, []);
    }

    public function getPagination(array $input): array
    {
        return [
            'page' => $input['page'],
            'per_page' => $input['per_page'],
        ];
    }
}
