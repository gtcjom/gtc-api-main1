<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiseaseHistoryResource;
use App\Services\DiseaseService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiseaseController extends  Controller
{

    public function store(DiseaseService $service, Request $request)
    {

        $request->validate([
            'patient_id' => ['required', Rule::exists('patients','patient_id')],
            'disease' => ['required', 'string', Rule::in(['covid','hepa','dengue'])],
            'date_started' => ['required','date','date_format:Y-m-d','before:today' ],

        ]);

       $history =  $service->make($request->patient_id, [
            'disease' => $request->disease,
            'date_started' =>  $request->date_started
        ]);


        return DiseaseHistoryResource::make($history);

    }
}