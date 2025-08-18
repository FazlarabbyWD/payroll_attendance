<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
// use Carbon\Carbon;

class LeaveStoreRequest extends FormRequest
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
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date'     => [
                'required',
                'date',
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) {
                    $start = $this->input('start_date');
                    if ($start && $value) {
                        $diff = \Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($value));
                        if ($diff > 5) {
                            $fail('The end date may not be more than 5 days after the start date.');
                        }
                    }
                },
            ],
            'leave_type' => ['required', Rule::in(['Sick', 'Casual', 'Annual', 'Unpaid', 'Other'])],
            'reason' => 'nullable|string|max:255',
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
            'employee_id.required' => 'The employee field is required.',
            'employee_id.exists' => 'The selected employee is invalid.',
            'start_date.required' => 'The start date field is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'start_date.before_or_equal' => 'The start date must be before or equal to the end date.',
            'end_date.required' => 'The end date field is required.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'leave_type.required' => 'The leave type field is required.',
            'leave_type.in' => 'The selected leave type is invalid.',
            'reason.string' => 'The reason must be a string.',
            'reason.max' => 'The reason may not be greater than 255 characters.',
        ];
    }
}
