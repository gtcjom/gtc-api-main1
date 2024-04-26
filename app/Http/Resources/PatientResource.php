<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'appointments' => $this->appointments ? AppointmentDataResource::collection($this->appointments) : [],
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'suffix' => $this->suffix ?: "",
            'middle' => $this->middle,
            'birthday' => $this->birthday,
            'civil_status' => $this->civil_status,
            'gender' => $this->gender,
            'street' => $this->street,
            'unit' => $this->unit,
            'floor' => $this->floor,
            'barangayData' => $this->whenLoaded('barangayData'),
            'barangay' => $this->barangay,
            // 'purokData' => $this->whenLoaded('purokData'),
            'purokData' => $this->purok,
            'purok' => $this->purok,
            'municipality' => $this->municipality,
            'municipalityData' => $this->whenLoaded('municipalityData'),
            'city' => $this->city,
            'zone' => $this->zone,
            'house_number' => $this->house_number,
            'subdivision' => $this->subdivision,
            'philhealth' => $this->philhealth,
            'patient_id' => $this->patient_id,
            'tin' => $this->tin,
            'household_id' => $this->household_id,
            'household' => HouseholdResource::make($this->whenLoaded('household')),
            'information' => PatientInformationResource::make($this->whenLoaded('information')),
            'avatar' => is_null($this->avatar) ? "" : Storage::url($this->avatar),
            'rawAnswer' => $this->whenLoaded('rawAnswer'),
            'head_relation' => $this->head_relation,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'altitude' => $this->altitude,
            'accuracy' => $this->accuracy,
            'diseases' => $this->whenLoaded('diseases'),
            'citizenship' => $this->citizenship,
            'mobile' => $this->mobile,
            'lmp' => $this->lmp,
            'respiratory' => $this->respiratory,
            'glucose' => $this->glucose,
            'uric_acid' => $this->uric_acid,
            'cholesterol' => $this->cholesterol,
            'height' => $this->height,
            'weight' => $this->weight,
            'pulse' => $this->pulse,
            'temperature' => $this->temperature,
            'blood_systolic' => $this->blood_systolic,
            'blood_diastolic' => $this->blood_diastolic,
            'prefix' => $this->prefix,
            'country' => $this->country,
            'region' => $this->region,
            'province' => $this->province,
            'landline' => $this->landline,
            'education_attainment' => $this->education_attainment,
            'employment_status' => $this->employment_status,
            'mother_firstname' =>  $this->mother_firstname,
            'mother_lastname' => $this->mother_lastname,
            'mother_middlename' => $this->mother_middlename,
            'indigenous' => (bool) $this->indigenous,
            'mother_birthdate' => $this->mother_birthdate,
            'family_member' => $this->family_member,
            'dswd_nhts' => (bool) $this->dswd_nhts,
            'family_serial_no' => $this->family_serial_no,
            'phil_health_member' => (bool) $this->phil_health_member,
            'phil_health_status_type' => $this->phil_health_status_type,
            'phil_health_category_type' => $this->phil_health_category_type,
            'direct_contributor' => $this->direct_contributor,
            'indirect_contributor' => $this->indirect_contributor,
            'pcb_eligble' => (bool) $this->pcb_eligble,
            'enlistment_date' => $this->enlistment_date,
            'profession' => $this->profession,
            'salary' => $this->salary,
            'religion' => $this->religion,
            'blood_type' => $this->blood_type,
            'zip_code' => $this->zip,
            'birthplace' => $this->birthplace,
            'email' => $this->email,
            'landline' => $this->landline,
            'telephone' => $this->telephone,
            'dependents' => $this->whenLoaded('patientDependents'),
            'social_history' => $this->whenLoaded('latestSocialHistory'),
            'environmental_history' => $this->whenLoaded('latestEnvironmentalHistory'),
            'verified' => $this->verified,
            'verified_at' => $this->verified_at,
            'verified_by' => $this->verified_by,
            'verified_by_entity' => $this->verified_by_entity,
            'pmrf_status' => $this->pmrf_status,
            'pmrf_status_detail' => $this->pmrf_status_detail,
            'pmrf' => $this->pmrf ? $this->pmrf : '',
            'signature' => $this->pmrf ? (is_null($this->pmrf->signature) ? "" : Storage::url($this->pmrf->signature)) : '',

        ];
    }
}
