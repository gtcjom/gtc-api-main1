<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{

    public function update(Request $request, string $id)
    {


        $request->validate([
            'nature_of_visit' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'age_in_year' => ['required', 'numeric'],
            'age_in_month' => ['required', 'numeric'],
            'age_in_day' => ['required', 'numeric'],
            'mode_of_transaction' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'numeric'],
            'height' => ['required', 'numeric'],
            'bmi' => ['required', 'numeric'],
            'bmi_category' => ['required', 'string', 'max:255'],
            'height_for_age' => ['required', 'numeric'],
            'weight_for_age' => ['required', 'numeric'],
            'attending_provider' => ['required', 'string', 'max:255'],
            'chief_complaint' => ['required', 'string', 'max:255'],
            'consent' => ['required', 'boolean'],
            'types' => ['required', 'array'],

        ]);

       Patient::query()->where('patient_id', $id)->firstOrFail();
        $consultation = new Consultation();
        $consultation->nature_of_visit = $request->nature_of_visit;
        $consultation->date = "{$request->date} {$request->time}}";
        $consultation->age_in_year = $request->age_in_year;
        $consultation->age_in_month = $request->age_in_month;
        $consultation->age_in_day = $request->age_in_day;
        $consultation->mode_of_transaction = $request->mode_of_transaction;
        $consultation->weight = $request->weight;
        $consultation->height = $request->height;
        $consultation->bmi = $request->bmi;
        $consultation->bmi_category = $request->bmi_category;
        $consultation->height_for_age = $request->height_for_age;
        $consultation->weight_for_age = $request->weight_for_age;
        $consultation->attending_provider = $request->attending_provider;
        $consultation->chief_complaint = $request->chief_complaint;
        $consultation->consent = $request->boolean('consent');
        $consultation->patient_id = $id;
        $consultation->save();

        foreach ($request->types as $type) {
            $consultation->types()->create([
                'types' => $type
            ]);
        }

        return ConsultationResource::make($consultation);

    }
}
