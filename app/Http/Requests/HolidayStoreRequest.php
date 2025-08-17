<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HolidayStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'start_date'   => 'required|date',
            'end_date'     => [
                'required',
                'date',
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) {
                    $start = $this->input('start_date');
                    if ($start && $value) {
                        $diff = \Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($value));
                        if ($diff > 10) {
                            $fail('The end date may not be more than 10 days after the start date.');
                        }
                    }
                },
            ],
            'is_recurring' => 'boolean',
            'description'  => 'nullable|string',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'title.required'          => 'The holiday title is required.',
            'title.string'            => 'The holiday title must be a valid string.',
            'title.max'               => 'The holiday title may not exceed 255 characters.',
            'start_date.required'     => 'The start date is required.',
            'start_date.date'         => 'The start date must be a valid date.',
            'end_date.required'       => 'The end date is required.',
            'end_date.date'           => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be the same or after the start date.',
            'is_recurring.boolean'    => 'The recurring field must be true or false.',
            'description.string'      => 'The description must be a valid string.',
        ];
    }
}
