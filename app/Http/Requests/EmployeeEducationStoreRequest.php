<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeEducationStoreRequest extends FormRequest
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
            'education' => 'required|array', // The 'education' array itself is required
            'education.*.id' => 'nullable|exists:employee_education,id', // Optional: for updating existing records
            'education.*.degree_name' => 'required|string|max:255',
            'education.*.field_of_study' => 'nullable|string|max:255',
            'education.*.institute_name' => 'required|string|max:255',
            'education.*.board' => 'nullable|string|max:255',
            'education.*.passing_year' => 'required|integer|min:1900|max:' . date('Y'), // Year cannot be in the future
            'education.*.gpa' => 'nullable|string|max:50',
            'education.*.certificate_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // Max 2MB
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
            'education.required' => 'At least one education entry is required.',
            'education.array' => 'Education data must be submitted as an array.',

            'education.*.degree_name.required' => 'The degree name is required for each education entry.',
            'education.*.degree_name.string' => 'The degree name must be a string.',
            'education.*.degree_name.max' => 'The degree name may not be greater than :max characters.',

            'education.*.field_of_study.string' => 'The field of study must be a string.',
            'education.*.field_of_study.max' => 'The field of study may not be greater than :max characters.',

            'education.*.institute_name.required' => 'The institute name is required for each education entry.',
            'education.*.institute_name.string' => 'The institute name must be a string.',
            'education.*.institute_name.max' => 'The institute name may not be greater than :max characters.',

            'education.*.board.string' => 'The board must be a string.',
            'education.*.board.max' => 'The board may not be greater than :max characters.',

            'education.*.passing_year.required' => 'The passing year is required for each education entry.',
            'education.*.passing_year.integer' => 'The passing year must be a whole number.',
            'education.*.passing_year.min' => 'The passing year must be at least :min.',
            'education.*.passing_year.max' => 'The passing year cannot be in the future.',

            'education.*.gpa.string' => 'The GPA/Division must be a string.',
            'education.*.gpa.max' => 'The GPA/Division may not be greater than :max characters.',

            'education.*.certificate_file.file' => 'The certificate file must be a valid file.',
            'education.*.certificate_file.mimes' => 'The certificate file must be a PDF, DOC, DOCX, JPG, or PNG format.',
            'education.*.certificate_file.max' => 'The certificate file must not be larger than :max kilobytes (2MB).',
        ];
    }
}
