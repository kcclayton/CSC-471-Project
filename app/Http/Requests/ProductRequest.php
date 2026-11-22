<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * The admin route group already checks the user is an admin.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Store SKUs in capitals, so "spr-001" and "SPR-001" count as the same SKU.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('sku')) {
            $this->merge(['sku' => strtoupper(trim((string) $this->input('sku')))]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'sku' => [
                'required', 'string', 'max:50', 'regex:/^[A-Z0-9\-]+$/',
                Rule::unique('products', 'sku')->ignore($this->route('product'), 'product_id'),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'integer', 'min:0', 'max:1000000'],
            'season_id' => ['required', 'integer', Rule::exists('seasons', 'season_id')],
            'stock_qty' => ['required', 'integer', 'min:0', 'max:1000000'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'remove_image' => ['boolean'],
        ];
    }

    /**
     * Friendlier field names for error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'sku' => 'SKU',
            'season_id' => 'season',
            'stock_qty' => 'stock',
        ];
    }
}
