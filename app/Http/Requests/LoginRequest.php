<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Changed to true, as you likely want to authorize the request.  If you need to check specific authorization logic, implement it here.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'password' => [
                'required',
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ];
    }

     /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email must not be greater than 255 characters.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number and one symbol.',
        ];
    }
}
