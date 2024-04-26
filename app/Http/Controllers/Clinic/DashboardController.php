<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Services\ClinicService;

class DashboardController extends Controller
{

    public function index(ClinicService $clinicService)
    {

        return $clinicService->dashboard();
    }
}
