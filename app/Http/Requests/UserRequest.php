<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'username' => ['required', 'string', 'min:2', 'max:50', Rule::unique('users', 'username')->ignore($this->id)],
            'password' => [Rule::requiredIf( fn() => is_null($this->id)), 'string', 'min:6', 'max:25', 'confirmed'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->id)],
            'type' => ['required', Rule::in(userTypes())],
            'municipality' => ['required', Rule::exists('municipalities','id')],
            'barangay' => ['required', Rule::exists('barangays','id')],
            'purok' => ['required', Rule::exists('puroks','id')],
            'name' => ['required', 'string', 'max:100'],
            'avatar' => ['nullable', 'image']
        ];
    }
}
