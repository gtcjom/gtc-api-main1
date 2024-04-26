<?php

namespace App\Http\Controllers\Clinic\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use  App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;

class PatientProfileController extends Controller
{


    public function update(PatientRequest $request, int $id)
    {
      $patient = Patient::query()->findOrFail($id);
      $patient->suffix = $request->get('suffix');
      $patient->prefix = $request->get('prefix');
      $patient->firstname = $request->get('firstname');
      $patient->middle = $request->get('middlename', '');
      $patient->lastname = $request->get('lastname');
      $patient->gender = $request->get('gender');
      $patient->birthday = $request->get('birthdate');
      $patient->street = $request->get('street','');
      $patient->zip = $request->get('zip_code','');


      $patient->birthplace = $request->get('birthplace');
      $patient->civil_status = $request->get('civil_status');
      $patient->education_attainment = $request->get('education_attainment',"");
      $patient->employment_status = $request->get('employment_status',"");


      $patient->religion = $request->get('religion',"");
      $patient->family_member = $request->get('family_member',"");


      $patient->indigenous = $request->boolean("indigenous");
      $patient->blood_type = $request->get('blood_type');

      $patient->mother_firstname = $request->get('mother_firstname',"");
      $patient->mother_lastname = $request->get('mother_lastname',"");
      $patient->mother_middlename = $request->get('mother_middlename',"");
      $patient->mother_birthdate = $request->get('mother_birthdate');

      $patient->country = $request->get('country',"");
      $patient->region = $request->get('region',"");
      $patient->province = $request->get('province',"");
      $patient->municipality_id = $request->get('municipality');
      $patient->barangay_id = $request->get('barangay');
      $patient->purok = $request->get('purok');

      $patient->telephone = $request->get('telephone');
      $patient->landline = $request->get('landline');
      $patient->mobile = $request->get('mobile');
      $patient->email = $request->get('email','');


      $patient->family_serial_no = $request->get('family_serial_no','');
      $patient->phil_health_category_type = $request->get('phil_health_category_type','');
     	$patient->enlistment_date = $request->get('enlistment_date');
      $patient->phil_health_status_type = $request->get('phil_health_status_type');
      $patient->family_member = $request->get('family_member','');

      $patient->pcb_eligble = $request->boolean('pcb_eligble');
      $patient->phil_health_member = $request->boolean('phil_health_member');
      $patient->dswd_nhts = $request->boolean('dswd_nhts');

      $patient->save();

      $patient->load(['municipalityData','barangayData']);

      $patient->municipality = $patient->municipalityData?->name ?? "";
      $patient->city = $patient->municipalityData?->name ?? "";
      $patient->barangay = $patient->barangayData?->name ?? "";

      $patient->save();


      return PatientResource::make($patient);
    }
}
