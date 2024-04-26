<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClinicReferralRequest extends FormRequest
{
	public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
			'patient_id' => ['required', Rule::exists('patients', 'id')],
			'referral_date' => ['required', 'date'],
			'notes' => ['required', 'string'],
			'from_clinic_id' => ['required', 'integer'],
			'to_clinic_id' => ['required', 'integer'],
			'diagnosis' => ['required', Rule::in(['tuberculosis', 'animal_bites', 'hypertension'])],
        ];
    }
}
