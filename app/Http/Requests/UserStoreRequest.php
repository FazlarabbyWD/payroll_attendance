<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
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
            'username'      => 'required|unique:users,username|min:4|max:50',
            'email'         => 'required|unique:users,email|email',
            'phone'         => ['required', 'regex:/^01[3-9]\d{8}$/'],
            'password'      => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'profile_photo' => 'nullable|file|mimes:jpg,bmp,png|max:1024|dimensions:min_width=100,min_height=200',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required'        => 'Username is required.',
            'username.unique'          => 'This username has already been taken.',
            'username.min'             => 'Username must be at least 4 characters.',
            'username.max'             => 'Username may not be greater than 50 characters.',

            'email.required'           => 'Email address is required.',
            'email.unique'             => 'This email has already been registered.',
            'email.email'              => 'Please provide a valid email address.',

            'phone.required'           => 'Phone number is required.',
            'phone.regex'              => 'Phone number must be a valid Bangladeshi number (e.g. 01XXXXXXXXX).',

            'password.required'        => 'Password is required.',
            'password.min'             => 'Password must be at least 6 characters.',
            'password.letters'         => 'Password must contain at least one letter.',
            'password.mixed'           => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers'         => 'Password must contain at least one number.',
            'password.symbols'         => 'Password must contain at least one symbol.',
            'password.uncompromised'   => 'The password has appeared in a data leak. Please choose a different one.',
          

            'profile_photo.file'       => 'The profile photo must be a file.',
            'profile_photo.max'        => 'The profile photo may not be larger than 1MB.',
            'profile_photo.dimensions' => 'Profile photo must be at least 100px wide and 200px high.',
        ];
    }
}
