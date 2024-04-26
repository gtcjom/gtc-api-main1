<?php

namespace App\Http\Controllers\Clinic\Doctor;

use App\Http\Controllers\Controller;
use App\Services\ClinicService;

class DoctorController extends Controller
{

    public function index(ClinicService $clinicService)
    {
        return $clinicService->getDoctors();
    }

    public function getDoctorByHealthUnit(ClinicService $clinicService)
    {
        return $clinicService->getDoctorByHealthUnit();
    }
}
