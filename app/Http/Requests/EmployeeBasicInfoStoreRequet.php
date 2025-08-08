<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class EmployeeBasicInfoStoreRequet extends FormRequest
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
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'date_of_joining' => 'required|date|before_or_equal:' . Carbon::now()->toDateString(),
            'employment_type_id' => 'required|exists:employment_types,id',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id', 
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
            'first_name.required' => 'The first name field is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name must not exceed 50 characters.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name must not exceed 50 characters.',
            'date_of_joining.required' => 'The date of joining field is required.',
            'date_of_joining.date' => 'The date of joining must be a valid date.',
            'date_of_joining.before_or_equal' => 'The date of joining must be today or a date in the past.',
            'employment_type_id.required' => 'The employment type field is required.',
            'employment_type_id.exists' => 'The selected employment type is invalid.',
            'department_id.required' => 'The department field is required.',
            'department_id.exists' => 'The selected department is invalid.',
            'designation_id.required' => 'The designation field is required.',
            'designation_id.exists' => 'The selected designation is invalid.',
        ];
    }
}
