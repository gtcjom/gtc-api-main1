<?php

namespace App\Http\Controllers;

use App\Http\Resources\PMRFResource;
use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Http\Response;

class PhilhealthMemberRegistrationController extends Controller
{
	public function show(int $id)
	{
		return response()->json([
			'data' => PMRFResource::make(Patient::findOrFail($id)->load('patientDependents', 'philhealthDetails')),
			'message' => 'Patient pmrf details retrieved successfully.'
		], Response::HTTP_OK);
	}
}
