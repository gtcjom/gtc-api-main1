<?php

namespace App\Http\Requests;

use App\Enums\CivilStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class PatientRequest extends FormRequest
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
            'firstname' => ['required', 'string', 'max:200'],
            'lastname' => ['required', 'string', 'max:200'],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'birthdate' => ['required', 'date', 'date_format:Y-m-d', 'before:today'],
            'barangay' => ['required'],
            'civil_status' => ['required'],
            'mother_firstname'  => ['required', 'string', 'max:100'],
            'mother_lastname'  => ['required', 'string', 'max:100'],
            'mother_middlename'  => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:10'],
            'region' => ['required', 'string', 'max:50'],
            'province'  => ['required', 'string', 'max:100'],
            'municipality' => ['required'], //Rule::exists('municipalities','id')],
            'barangay' => ['required'], // Rule::exists('barangays','id')],
            'zip_code'  => ['required', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:500'],
            'floor' => ['nullable', 'string', 'max:200'],
            'direct_contributor' => ['nullable', 'string', 'max:200'],
            'indirect_contributor' => ['nullable', 'string', 'max:200'],
            'profession' => ['nullable', 'string', 'max:200'],
            'salary' => ['nullable', 'string', 'max:200'],
            'prefix' => ['nullable', 'string', 'max:200'],
            'suffix' => ['nullable', 'string', 'max:200'],
            'middlename' => ['nullable', 'string', 'max:200'],
            'birthplace' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'philhealth' => ['nullable', 'string', 'max:255'],
            'education_attainment' => ['nullable', 'string', 'max:100'],
            'employment_status' => ['nullable', 'string', 'max:100'],
            'religion' => ['nullable', 'string', 'max:100'],
            'purok' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'string', 'max:200'],
            'mobile' => ['nullable', 'string', 'max:200'],
            'telephone' => ['nullable', 'string', 'max:200'],
            'phil_health_category_type' => ['nullable', 'string', 'max:50'],
            'phil_health_status_type' => ['nullable', 'string', 'max:50'],
            'enlistment_date' => ['nullable', 'date'],
            'family_serial_no'  => ['nullable', 'string', 'max:50'],
            'family_member' => ['nullable', 'string', 'max:50'],
            'blood_type' => ['nullable', 'string', 'max:20'],
            'mother_birthdate' => ['nullable', 'date', 'date_format:Y-m-d', 'before:today'],
            'patientDependents' => ['nullable', 'array'],
            'patientDependents.*.firstname' => ['nullable', 'string', 'max:200'],
            'patientDependents.*.lastname' => ['nullable', 'string', 'max:200'],
            'patientDependents.*.middle_name' => ['nullable', 'string', 'max:200'],
            'patientDependents.*.name_extension' => ['nullable', 'string', 'max:200'],
            'patientDependents.*.relationship' => ['nullable', 'string', 'max:200'],


        ];
    }
}
