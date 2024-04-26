<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientRemark;
use App\Services\PatientService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EditRemarksController extends Controller
{

    public function store(Request $request, PatientService $patientService)
    {
        $request->validate([
            'remark' => ['required','string'],
            'patient_id' => ['required', 'string', Rule::exists('patients','patient_id')]
        ]);

        $remarks = new PatientRemark;
        $remarks->patient_id = $request->get('patient_id');
        $remarks->remark = $request->get('remark');
        $remarks->save();

        $patient = Patient::query()->where('patient_id', $request->get('patient_id'))->first();

        if(!is_null($patient->kobotools_id)){

            return [
                'remarks' => $remarks,
            ];
        }

        return [
            'remarks' => $remarks
        ];


    }

    public function update(PatientService $patientService)
    {

    }
}