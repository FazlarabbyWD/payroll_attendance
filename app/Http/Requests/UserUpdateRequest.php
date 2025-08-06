<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
// Import the Rule class

class UserUpdateRequest extends FormRequest
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


     $userId = $this->route('user');
    return [
       'username' => [
            'required',
            'min:4',
              Rule::unique('users')->ignore($userId),
        ],
       'email' => [
            'required',
            'email',
            Rule::unique('users')->ignore($userId),
        ],
     'phone' => [
            'required',
            'regex:/^01[3-9]\d{8}$/',
            Rule::unique('users')->ignore($userId),
        ],
        'profile_photo' => 'nullable|file|mimes:jpg,bmp,png|max:1024|dimensions:min_width=100,min_height=200',
    ];
}


    public function messages(): array
    {
        return [
            'username.required'        => 'Username is required.',

            'username.min'             => 'Username must be at least 4 characters.',
            'username.max'             => 'Username may not be greater than 50 characters.',

            'email.required'           => 'Email address is required.',

            'email.email'              => 'Please provide a valid email address.',

            'phone.required'           => 'Phone number is required.',
            'phone.regex'              => 'Phone number must be a valid Bangladeshi number (e.g. 01XXXXXXXXX).',

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
