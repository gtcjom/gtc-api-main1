<?php

namespace App\Http\Controllers\Clinic\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentDataResource;
use App\Models\AppointmentData;
use App\Models\Patient;
use App\Models\Vital;
use Illuminate\Http\Request;

class TbVitalsController extends Controller
{

    public function update(Request $request, int $id)
    {
        $appointment = AppointmentData::findOrFail($id);

        $patient = Patient::query()->findOrFail($appointment->patient_id);
        $patient->lmp = $request->has('lmp') ? $request->get('lmp') : $patient->lmp;
        $patient->rispiratory = $request->get('respiratory');
        $patient->glucose = $request->get('glucose');
        $patient->uric_acid = $request->get('uric_acid');
        $patient->cholesterol = $request->get('cholesterol');
        $patient->height = $request->get('height');
        $patient->weight = $request->get('weight');
        $patient->pulse = $request->get('pulse');
        $patient->temperature = $request->get('temperature');
        $patient->blood_systolic = $request->get('blood_systolic');
        $patient->blood_diastolic = $request->get('blood_diastolic');
        $patient->save();

        if ($appointment->vital_id) {
            $vital = Vital::findOrFail($appointment->vital_id);
        } else {
            $vital = new Vital();
        }

        $vital->patient_id = $appointment->patient_id;
        $vital->added_by_id = $request->user()->id;
        $vital->temperature = $request->get('temperature');
        $vital->blood_pressure = $request->get('blood_pressure') ?: "";
        $vital->weight = $request->get('weight');
        $vital->height = $request->get('height');
        $vital->respiratory = $request->get('respiratory');
        $vital->uric_acid = $request->get('uric_acid');
        $vital->cholesterol = $request->get('cholesterol');
        $vital->glucose = $request->get('glucose');
        $vital->pulse = $request->get('pulse');
        $vital->blood_systolic = $patient->blood_systolic;
        $vital->blood_diastolic = $patient->blood_diastolic;
        $vital->save();

        $appointment->vital_id = $vital->id;
        $appointment->save();
        $appointment->load('vitals');

        return AppointmentDataResource::make($appointment);
    }
}
