<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\Request;

class PhilHealthController extends Controller
{


    public function update(Request $request, int $id){

        $patient = Patient::query()->findOrFail($id);
        $patient->prefix = $request->get('prefix');
        $patient->lastname = $request->get('lastname');
        $patient->firstname = $request->get('firstname');
        $patient->middle = $request->get('middle');
        $patient->suffix = $request->get('suffix');
        $patient->gender = $request->get('gender');
        $patient->birthday = $request->get('birthday');
        $patient->birthplace = $request->get('birthplace');
        $patient->civil_status = $request->get('civil_status');
        $patient->blood_type = $request->get('blood_type');
        $patient->education_attainment = $request->get('education_attainment');
        $patient->employment_status = $request->get('employment_status');
        $patient->indigenous = $request->boolean('indigenous');
        $patient->mother_firstname = $request->get('mother_firstname');
        $patient->mother_lastname = $request->get('mother_lastname');
        $patient->mother_middlename = $request->get('mother_middlename');
        $patient->mother_birthdate = $request->get('mother_birthdate');

        $patient->country = $request->get('country');
        $patient->province = $request->get('province');
        $patient->municipality_id = $request->get('municipality_id');
        $patient->barangay_id = $request->get('barangay_id');
        $patient->zip = $request->get('zip');
        $patient->email = $request->get('email');
        $patient->mobile = $request->get('mobile');
        $patient->landline = $request->get('landline');

        $patient->family_member = $request->get('family_member');
        $patient->dswd_nhts = $request->boolean('dswd_nhts');
        $patient->family_serial_no = $request->get('family_serial_no');
        $patient->phil_health_member = $request->boolean('phil_health_member');
        $patient->phil_health_status_type = $request->get('phil_health_status_type');
        $patient->philhealth = $request->get('philhealth');
        $patient->phil_health_category_type = $request->get('phil_health_category_type');
        $patient->pcb_eligble = $request->boolean('pcb_eligble');
        $patient->enlistment_date = $request->get('enlistment_date');

        if($request->hasFile('avatar')){
            $patient->avatar = $request->file('avatar')->store('patients/avatar');
        }

        $patient->save();


        return PatientResource::make($patient);
    }
}

