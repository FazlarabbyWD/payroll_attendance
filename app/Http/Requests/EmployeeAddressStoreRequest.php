<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeAddressStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'        => ['required', Rule::in(['permanent', 'current'])],
            'country'     => ['required', 'string', 'max:255'],
            'state'       => ['required', 'string', 'max:255'],
            'city'        => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'address'     => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'    => 'The address type is required.',
            'type.in'          => 'The address type must be either permanent or current.',
            'country.required' => 'The country field is required.',
            'state.required'   => 'The state field is required.',
            'city.required'    => 'The city field is required.',
            'postal_code.required' => 'The postal code field is required.',
            'address.required' => 'The address field is required.',
        ];
    }
}
