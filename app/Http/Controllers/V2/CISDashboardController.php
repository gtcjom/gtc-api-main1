<?php

namespace App\Http\Controllers\V2;

use App\Enums\TBProgramStatusEnum;
use App\Enums\TBStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\TuberculosisProgramResource;
use App\Models\Patient;
use App\Models\TuberculosisProgram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CISDashboardController extends Controller
{
    public function population()
	{
		// data from GTC
		$totalPopulation = 120103;

		$surveyedPatients = DB::table('patients')->count();
		$percentage = round(($surveyedPatients /  $totalPopulation) * 100, 2);

		// total number of patients that is referred to RHU
		$totalRefferedByBarangay =  DB::table('tuberculosis_programs')->count();

		// total number of patients that is approved and pending for SPH TB Treatment Program
		$totalTBPositivePatients = DB::table('tuberculosis_programs')
			->whereNotNull('refer_by_rhu')
			->whereIn('status', [TBStatusEnum::Approved, TBStatusEnum::Pending])
			->count();

		// total number of treated patients
		$totalTreatedPatients = DB::table('tuberculosis_programs')
			->where('program_status','treated')
			->count();

		$data = [
			"total_population" => $totalPopulation,
			"total_surveyed_patients" => $surveyedPatients,
			"percentage" => $percentage,
			"total_referred_by_barangay" => $totalRefferedByBarangay,
			"total_tb_positive_patients" => $totalTBPositivePatients,
			"total_treated_patients" => $totalTreatedPatients,
		];

		return response()->json([
			"data" => $data,
			"message" => "Population retrieved successfully."
		], Response::HTTP_OK);
	}

	public function tbPatientsfromBarangayToRHU()
	{
		return TuberculosisProgramResource::collection(TuberculosisProgram::latest()->get()
			->load('patient', 'barangay', 'barangayClinic', 'municipalityClinic')
		);
	}

	public function tbPositiveList(Request $request)
	{
		$status = $request->has('status') ? $request->status : null;

		// return list of TB positive patients referred to sph
		return TuberculosisProgramResource::collection(TuberculosisProgram::query()
			->when($status, function($query, $status){
				return $query->where('status', $status);
			})
			->whereNotNull('status')
			->latest()
			->get()
			->load('patient', 'barangay', 'barangayClinic', 'municipalityClinic')
		);
	}

	public function treatedPatientPerMunicipality(Request $request)
	{
		$municipality = $request->has('municipality') ? $request->municipality : null;

		return TuberculosisProgramResource::collection(TuberculosisProgram::query()
			->when($municipality, function($query, $municipality){
				return $query->whereHas('barangay.municipality', function($query) use ($municipality){
					$query->where('municipality_id', $municipality);
				});
			})
			->where('program_status', TBProgramStatusEnum::Treated)
			->latest()
			->get()
			->load('patient', 'barangay', 'barangayClinic', 'municipalityClinic')
		);
	}
}
