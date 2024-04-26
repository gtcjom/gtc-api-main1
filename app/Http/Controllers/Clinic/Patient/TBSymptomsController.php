<?php

namespace App\Http\Controllers\Clinic\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentDataResource;
use App\Models\AppointmentData;
use App\Models\TuberculosisData;
use Illuminate\Http\Request;

class TBSymptomsController extends Controller
{

    public function update(Request $request,int $id)
    {
        $appointment = AppointmentData::findOrFail($id);

        if(is_null($appointment->tb_data_id)){
            $tb = new TuberculosisData();

        }else{
            $tb = TuberculosisData::findOrFail($appointment->tb_data_id);
        }

        $tb->cough_for_3_weeks_or_longer = $request->boolean('cough_for_3_weeks_or_longer');
        $tb->coughing_up_blood_or_mucus = $request->boolean('coughing_up_blood_or_mucus');
        $tb->chest_pain = $request->boolean('chest_pain');
        $tb->pain_with_breathing_or_coughing = $request->boolean('pain_with_breathing_or_coughing');
        $tb->fever = $request->boolean('fever');
        $tb->chills = $request->boolean('chills');
        $tb->night_sweats = $request->boolean('night_sweats');
        $tb->weight_loss = $request->boolean('weight_loss');
        $tb->not_wanting_to_eat = $request->boolean('not_wanting_to_eat');
        $tb->tiredness = $request->boolean('tiredness');
        $tb->not_feeling_well_in_general = $request->boolean('not_feeling_well_in_general');
        $tb->save();

        $appointment->tb_data_id = $tb->id;
        $appointment->save();

        $appointment->load('tb_symptoms');

        return AppointmentDataResource::make($appointment);



    }
}
