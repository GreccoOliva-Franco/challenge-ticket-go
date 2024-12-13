<?php

namespace App\Http\Requests;

class GetProductsRequest extends PaginatedRequest
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
        return array_merge(
            parent::rules(),
            [
                'vendor_id' => 'nullable|integer|min:1',
                'name' => 'nullable|string',
            ]
        );
    }

    public function getFilter(array $input): array {
        return array_reduce($input, $this->removePaginationKeys, []);
    }
}
