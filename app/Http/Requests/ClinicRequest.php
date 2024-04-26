<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClinicRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', Rule::in(['purok', 'barangay', 'municipality'])],
            'purok_id' => ['nullable'], // Rule::exists('puroks','id')],
            'barangay_id' => ['nullable'], // Rule::exists('barangays','id')],
            'municipality_id' => ['nullable'], // Rule::exists('barangays','id')],
            'region' => ['nullable'], // Rule::exists('barangays','id')],
            'province' => ['nullable'], // Rule::exists('barangays','id')],
            'municipality' => ['nullable'], // Rule::exists('barangays','id')],
            'barangay' => ['nullable'], // Rule::exists('barangays','id')],
            'purok' => ['nullable'], // Rule::exists('barangays','id')],
            // 'municipality_id' => ['nullable', Rule::exists('municipalities', 'id')],
            'street' =>  ['required', 'string', 'max:150'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'tuberculosis' => ['nullable', Rule::in([1, 0])],
            'animal_bites' => ['nullable', Rule::in([1, 0])],
            'hypertension' => ['nullable', Rule::in([1, 0])],
            'image' => ['nullable', 'image'],
        ];
    }
}
