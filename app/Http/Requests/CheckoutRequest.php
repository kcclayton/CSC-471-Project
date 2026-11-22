<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
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
            'ship_name' => ['required', 'string', 'max:255'],
            'ship_street' => ['required', 'string', 'max:255'],
            'ship_city' => ['required', 'string', 'max:255'],
            'ship_state' => ['required', 'string', 'max:50'],
            'ship_zip' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9\-\s]{3,20}$/'],
            'ship_phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+().\-\s]{7,30}$/'],
            'ship_email' => ['required', 'string', 'email', 'max:255'],
        ];
    }

    /**
     * Friendlier field names for error messages, e.g. "The phone field is required."
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'ship_name' => 'full name',
            'ship_street' => 'street address',
            'ship_city' => 'city',
            'ship_state' => 'state',
            'ship_zip' => 'ZIP code',
            'ship_phone' => 'phone',
            'ship_email' => 'email',
        ];
    }
}
