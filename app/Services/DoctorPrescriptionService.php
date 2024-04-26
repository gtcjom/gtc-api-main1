<?php

namespace App\Services;

class DoctorPrescriptionService
{

    public function index(string $class)
    {
		return $class::where('patient_id', request('patient_id'))
					->orderBy('created_at')
					->get();
	}
}