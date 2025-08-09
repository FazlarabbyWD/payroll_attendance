<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeePersonalInfoStoreRequest extends FormRequest
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
            'employee_id'       => 'required|integer|exists:employees,id',
            'phone_no'          => 'required|string|regex:/^01[3-9]\d{8}$/',
            'email'             => 'nullable|email|max:255',
            'date_of_birth'     => 'required|date|before:today',
            'gender_id'         => 'required|integer|exists:genders,id',
            'religion_id'       => 'required|integer|exists:religions,id',
            'marital_status_id' => 'required|integer|exists:marital_statuses,id',
            'blood_group_id'    => 'required|integer|exists:blood_groups,id',
            'national_id'       => 'required|string|min:10|max:20',
            'type'              => 'required|in:current,permanent',
            'country'           => 'required|string|max:100',
            'state'             => 'required|string|max:100',
            'city'              => 'required|string|max:100',
            'postal_code'       => 'required|string|max:10',
            'address'           => 'required|string|min:5|max:1000',
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'employee_id.required'       => 'The employee is required.',
            'employee_id.exists'         => 'The selected employee does not exist.',

            'phone_no.required'          => 'Phone number is required.',
            'phone_no.regex'             => 'Phone number must be a valid Bangladeshi mobile number.',

            'email.email'                => 'Please enter a valid email address.',
            'email.max'                  => 'Email must not exceed 255 characters.',

            'date_of_birth.required'     => 'Date of birth is required.',
            'date_of_birth.before'       => 'Date of birth must be before today.',

            'gender_id.required'         => 'Gender is required.',
            'gender_id.exists'           => 'Selected gender is invalid.',

            'religion_id.required'       => 'Religion is required.',
            'religion_id.exists'         => 'Selected religion is invalid.',

            'marital_status_id.required' => 'Marital status is required.',
            'marital_status_id.exists'   => 'Selected marital status is invalid.',

            'blood_group_id.required'    => 'Blood group is required.',
            'blood_group_id.exists'      => 'Selected blood group is invalid.',

            'national_id.required'       => 'National ID is required.',
            'national_id.min'            => 'National ID must be at least 10 digits.',
            'national_id.max'            => 'National ID must not be more than 20 digits.',

            'address_type.required'      => 'Address type is required.',
            'address_type.in'            => 'Address type must be either current or permanent.',

            'country.required'           => 'Country is required.',
            'state.required'             => 'State is required.',
            'city.required'              => 'City is required.',

            'postal_code.required'       => 'Postal code is required.',
            'postal_code.max'            => 'Postal code must not exceed 10 characters.',

            'address.required'           => 'Address is required.',
            'address.min'                => 'Address must be at least 5 characters long.',
            'address.max'                => 'Address must not exceed 1000 characters.',
        ];
    }
}
