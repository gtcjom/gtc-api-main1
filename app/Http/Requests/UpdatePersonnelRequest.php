<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonnelRequest extends FormRequest
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
            'street' => ['nullable'],
            'region' => ['nullable'],
            'province' => ['nullable'],
            'municipality' => ['nullable'],
            'barangay' => ['nullable'],
            'purok' => ['nullable'],
            'type' => ['required', 'string', Rule::in(userClinicTypes())],
            'avatar' => ['nullable', 'image'],
            'name' => ['required', 'string', 'max:100'],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'birthdate' => ['required', 'date', 'date_format:Y-m-d', 'before:today'],
            'contact_number' => ['required', 'string'],
        ];
    }
}
