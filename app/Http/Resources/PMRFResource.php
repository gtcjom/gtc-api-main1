<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PMRFResource extends JsonResource
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
			'lastname' => $this->lastname,
			'firstname' => $this->firstname,
			'middle' => $this->middle,
			'mother_firstname' => $this->mother_firstname,
			'mother_lastname' => $this->mother_lastname,
			'mother_middlename' => $this->mother_middlename,
			'spouse_last_name' => $this->spouse_last_name,
			'spouse_first_name' => $this->spouse_first_name,
			'spouse_name_ext' => $this->spouse_name_ext,
			'spouse_middle_name' => $this->spouse_middle_name,
			'date_of_birth' => Carbon::parse($this->birthday)->format('mm-dd-yyyy'),
			'birthplace' => $this->birthplace,
			'philsys_id_no' =>$this->philsys_id_no,
			'gender' => $this->gender,
			'civil_status' => $this->civil_status,
			'citizenship' => $this->citizenship,
			'tin' => $this->tin,
			'building_name' => $this->building_name,
			'house_number' => $this->house_number,
			'subdivision' => $this->subdivision,
			'barangay' => $this->barangay,
			'municipality' => $this->municipality,
			'province' => $this->province,
			'zip' => $this->zip,
			'is_mail_address' => $this->is_mail_address,
			'mail_building_name' => $this->mail_building_name,
			'mail_address_block' => $this->mail_address_block,
			'main_street' => $this->main_street,
			'mail_subdivision' => $this->mail_subdivision,
			'mail_barangay' => $this->mail_barangay,
			'mail_city' => $this->mail_city,
			'mail_zip_code' => $this->mail_zip_code,
			'telephone' => $this->telephone,
			'mobile' => $this->mobile,
			'business_direct_line' => $this->business_direct_line,
			'email' => $this->email,
			'telephone' => $this->telephone,
			'dependents' => $this->whenLoaded('patientDependents'),
			'philhealth_details' => $this->whenLoaded('philhealthDetails')
		];
    }
}
