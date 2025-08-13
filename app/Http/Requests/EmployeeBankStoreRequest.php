<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeBankStoreRequest extends FormRequest
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
            'bank_id'             => ['required', 'integer', 'exists:banks,id'],
            'bank_branch_id'           => ['required', 'integer', 'exists:bank_branches,id'],
            'account_holder_name' => ['required', 'string', 'max:255'],
            'account_number'      => ['required', 'string', 'max:30', 'regex:/^[0-9]+$/'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'bank_id.required'             => 'Please select a bank.',
            'bank_id.integer'              => 'Invalid bank ID format.',
            'bank_id.exists'                => 'The selected bank does not exist.',

            'branch_id.required'           => 'Please select a branch.',
            'branch_id.integer'            => 'Invalid branch ID format.',
            'branch_id.exists'               => 'The selected branch does not exist.',

            'account_holder_name.required' => 'Account holder name is required.',
            'account_holder_name.string'   => 'Account holder name must be a valid string.',
            'account_holder_name.max'      => 'Account holder name may not be greater than 255 characters.',

            'account_number.required'      => 'Account number is required.',
            'account_number.string'        => 'Account number must be a valid string.',
            'account_number.max'           => 'Account number may not be greater than 30 characters.',
            'account_number.regex'         => 'Account number must contain only numbers.',
        ];
    }
}
