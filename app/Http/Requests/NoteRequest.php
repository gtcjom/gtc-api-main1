<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoteRequest extends FormRequest
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
            'title' => ['nullable','string','max:200'],
            'description' => ['required','string','max:600000'],
            'date' => ['required','date'],
            'time' => ['required'],
            'patient_id' => ['required', Rule::exists('patients','id')]
        ];
    }
}
