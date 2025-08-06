<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DesignationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow request, add auth logic if needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('designations', 'name')->ignore($this->designation),
            ],
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required'  => 'The designation name is required.',
            'name.string'    => 'The designation name must be a string.',
            'name.max'       => 'The designation name must not exceed 255 characters.',
            'name.unique'    => 'This designation name already exists.',

            'description.string' => 'The description must be a string.',
            'description.max'    => 'The description must not exceed 1000 characters.',
        ];
    }
}
