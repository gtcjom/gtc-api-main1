<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClinicReferralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
			'referral_date' => ['required', 'date'],
			'notes' => ['required', 'string'],
			'to_clinic_id' => ['required', 'integer'],
			'diagnosis' => ['required', Rule::in(['tuberculosis', 'animal_bites', 'hypertension'])],
        ];
    }
}
