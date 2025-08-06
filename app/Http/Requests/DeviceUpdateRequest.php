<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Change this as needed for authorization
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'device_name' => [
                'required',
                'string',
                'min:4',
                'max:50',
                Rule::unique('devices', 'device_name')->ignore($this->device),
            ],
            'location'    => 'required|string|max:255',
            'ip_address'  => 'required|ip',
            'port'        => [
                'required',
                'integer',
                Rule::unique('devices')->where(function ($query) {
                    return $query->where('ip_address', $this->ip_address);
                })->ignore($this->device),
            ],
             'status'  => 'required|integer|in:0,1',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'device_name.required' => 'Device name is required.',
            'device_name.unique'   => 'This device name has already been taken.',
            'device_name.min'      => 'Device name must be at least 4 characters.',
            'device_name.max'      => 'Device name may not be greater than 50 characters.',
            'device_name.string'   => 'Device name must be a string.',

            'location.required'    => 'Location is required.',
            'location.string'      => 'Location must be a string.',
            'location.max'         => 'Location may not be greater than 255 characters.',

            'ip_address.required'  => 'IP address is required.',
            'ip_address.ip'        => 'The IP address format is invalid.',

            'port.required'        => 'Port is required.',
            'port.integer'         => 'Port must be an integer.',
            'port.unique'          => 'The combination of IP address and port is already in use.',
        ];
    }
}
