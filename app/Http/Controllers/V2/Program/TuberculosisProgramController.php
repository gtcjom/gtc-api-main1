<?php

namespace App\Http\Controllers\V2\Program;

use App\Http\Controllers\Controller;
use App\Http\Resources\TuberculosisProgramResource;
use App\Models\Patient;
use App\Models\TuberculosisProgram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TuberculosisProgramController extends Controller
{
	public function show(TuberculosisProgram $tuberculosisProgram)
	{
		return response()->json([
			'data' => new TuberculosisProgramResource($tuberculosisProgram->load('patient')),
			'message' => 'Tuberculosis program entry retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function barangayList(Request $request)
	{
		$barangay_id = $request->barangay_id;

		return TuberculosisProgramResource::collection(
			TuberculosisProgram::query()
				->where('status', null)
				->when($barangay_id, function ($query) use ($barangay_id) {
					return $query->where('barangay_id', $barangay_id);
				})
				->latest()
				->get()
		);
	}

	public function createFromBarangay(Request $request)
	{
		// validate data
		$data = $request->validate([
			'patient_id' => ['required', 'string', Rule::exists('patients', 'id')],
			'address' => ['nullable', 'string'],
			'barangay_id' => ['nullable', 'string'],
			'program' => ['nullable', 'string'],
			'rhu' => ['required', 'string'],
			'brgy_refferal_date' => ['required', 'date'],
			'refer_by_brgy_asst' => ['nullable'],
			'brgy_notes' => ['required', 'string'],
			'barangay_clinic_id' => ['required', 'string'],
			'municipality_clinic_id' => ['required', 'string'],
		]);

		// create tb program entry
		$tuberculosisProgram = TuberculosisProgram::create(array_merge(
			$data,
			[
				'program' => 'tuberculosis program',
				'refer_by_brgy_asst' => $request->user()->id,
			]
		));

		return response()->json([
			'data' => new TuberculosisProgramResource($tuberculosisProgram),
			'message' => 'Tuberculosis program entry created successfully.'
		], Response::HTTP_CREATED);
	}

	public function updateFromBarangay(Request $request, TuberculosisProgram $tuberculosisProgram)
	{
		// validate data
		$data = $request->validate([
			'address' => ['nullable', 'string'],
			'rhu' => ['required', 'string'],
			'brgy_refferal_date' => ['required', 'date'],
			'brgy_notes' => ['required', 'string'],
			'municipality_clinic_id' => ['required', 'string'],
		]);

		// update tb program entry
		$tuberculosisProgram->update(array_merge($data));

		return response()->json([
			'data' => new TuberculosisProgramResource($tuberculosisProgram),
			'message' => 'Tuberculosis program entry updated successfully.'
		], Response::HTTP_OK);
	}

	public function receiveBarangayReferral(Request $request, TuberculosisProgram $tuberculosisProgram)
	{
		$tuberculosisProgram->update(array_merge([
			'date_received_by_rhu' => Carbon::now(),
			'received_by_rhu_id' => $request->user()->id
		]));

		return response()->json(['message' => 'TB Program request was successfully received by sph.'], Response::HTTP_OK);
	}

	public function rhuToSphReferral(Request $request, TuberculosisProgram $tuberculosisProgram)
	{
		// validate data
		$data = $request->validate([
			'rhu_refferal_date' => ['required', 'date'],
			'refer_by_rhu' => ['nullable'],
			'rhu_notes' => ['required', 'string'],
			'status' => ['nullable', 'string'],
			'hospital_id' => ['required'],
		]);

		// add referral from rhu to sph
		$tuberculosisProgram->update(array_merge(
			$data,
			[
				'refer_by_rhu' => $request->user()->id,
				'status' => 'pending'
			]
		));

		return response()->json([
			'data' => new TuberculosisProgramResource($tuberculosisProgram),
			'message' => 'Patient was successfully reffered to sph.'
		], Response::HTTP_OK);
	}

	public function updateRhuToSphReferral(Request $request, TuberculosisProgram $tuberculosisProgram)
	{
		// validate data
		$data = $request->validate([
			'rhu_refferal_date' => ['required', 'date'],
			'rhu_notes' => ['required', 'string'],
			'hospital_id' => ['required'],
		]);

		// update referral from rhu to sph
		$tuberculosisProgram->update(array_merge($data));

		return response()->json([
			'data' => new TuberculosisProgramResource($tuberculosisProgram),
			'message' => 'Refferal to sph was successfully updated.'
		], Response::HTTP_OK);
	}

	public function rhuList(Request $request)
	{
		$municipality_clinic_id = $request->municipality_clinic_id;

		return TuberculosisProgramResource::collection(
			TuberculosisProgram::query()
				->when($municipality_clinic_id, function ($query) use ($municipality_clinic_id) {
					return $query->where('municipality_clinic_id', $municipality_clinic_id);
				})
				->whereNotNull('rhu')
				->latest()
				->get()
		);
	}

	public function receiveSphReferral(Request $request, TuberculosisProgram $tuberculosisProgram)
	{
		$tuberculosisProgram->update(array_merge([
			'date_received_by_sph' => Carbon::now(),
			'received_by_sph_id' => $request->user()->id
		]));

		return response()->json(['message' => 'TB Program request was successfully received by hospital.'], Response::HTTP_OK);
	}

	public function approve(Request $request, TuberculosisProgram $tuberculosisProgram)
	{
		// approve TB program application
		$tuberculosisProgram->update(array_merge([
			'date_approved' => Carbon::now(),
			'approved_by' => $request->user()->id,
			'status' => 'approved'
		]));

		return response()->json([
			'data' => new TuberculosisProgramResource($tuberculosisProgram),
			'message' => 'Patient was successfully approved for TB Program.'
		], Response::HTTP_OK);
	}

	public function sphList()
	{
		return TuberculosisProgramResource::collection(TuberculosisProgram::query()->where('status', 'approved')->latest()->get());
	}

	public function existingTBProgramReferral(Patient $patient)
	{
		if ($patient->existingTBProgramReferral()) {
			return response()->json([
				'data' => new TuberculosisProgramResource($patient->existingTBProgramReferral()->load('patient')),
				'message' => 'Patient TB Program Referral successfully retrieved.'
			], Response::HTTP_OK);
		} else {
			return response()->json(['message' => 'Patient currently does not have TB Program Referral'], Response::HTTP_OK);
		}
	}
}
