<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonnelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => ['required', 'string', 'min:1', 'max:15', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6', 'max:25', 'confirmed'],
            'street' => ['nullable'],
            'region' => ['nullable'],
            'province' => ['nullable'],
            'municipality' => ['nullable'],
            'barangay' => ['nullable'],
            'purok' => ['nullable'],
            'type' => ['nullable', 'string', Rule::in(userClinicTypes())],
            'avatar' => ['nullable', 'image'],
            'name' => ['required', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', Rule::in(['Male', 'Female'])],
            'birthdate' => ['nullable', 'date', 'date_format:Y-m-d', 'before:today'],
            'contact_number' => ['nullable', 'string'],
        ];
    }
}
