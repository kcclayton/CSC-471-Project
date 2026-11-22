<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductAttributeRequest extends FormRequest
{
    /**
     * Keep attribute errors separate from the product form's errors on the same page.
     *
     * @var string
     */
    protected $errorBag = 'productAttributes';

    /**
     * The admin route group already checks the user is an admin.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'attr_name' => [
                'required', 'string', 'max:255',
                Rule::unique('product_attributes', 'attr_name')
                    ->where('product_id', $this->route('product'))
                    ->ignore($this->route('attribute'), 'attribute_id'),
            ],
            'attr_value' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Friendlier field names for error messages. (Laravel calls these "attributes",
     * which is unrelated to product attributes.)
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'attr_name' => 'attribute name',
            'attr_value' => 'value',
        ];
    }
}
