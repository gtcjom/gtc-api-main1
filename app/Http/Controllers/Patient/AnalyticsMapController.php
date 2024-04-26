<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiseaseHistoryResource;
use App\Services\DiseaseService;

class AnalyticsMapController extends Controller
{

    public function index(DiseaseService $service)
    {
        return DiseaseHistoryResource::collection($service->getMap());
    }
}