<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeasonRequest extends FormRequest
{
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
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('seasons', 'name')->ignore($this->route('season'), 'season_id'),
            ],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
