<?php

namespace App\Http\Controllers\Clinic;

use App\Enums\PatientQueueStatusEnum;
use App\Events\ClinicQueueEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\PatientQueueResource;
use App\Http\Resources\PatientResource;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientQueue;
use App\Services\ClinicService;
use App\Services\VitalService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class QueueController extends Controller
{

	public function show(PatientQueue $patientQueue)
	{
		return response()->json([
			'data' => new PatientQueueResource($patientQueue->load('patient', 'doctor')),
			'message' => 'Patient queue data retrieved.'
		], Response::HTTP_OK);
	}

	public function store(VitalService $vitalService, Request $request)
	{
		$request->validate([
			'patient_id' => ['required'],
			'purpose' => ['required', 'string'],
			'doctor_id' => ['required'],
			'clinic_id' => ['required'],
			'type_service' => ['required'],
			'temperature' => ['required', 'numeric'],
			'respiratory' => ['nullable', 'numeric'],
			'glucose' => ['nullable', 'numeric'],
			'cholesterol' => ['nullable', 'numeric'],
			'pulse' => ['nullable', 'numeric'],
			'weight' => ['required', 'numeric'],
			'height' => ['required', 'numeric'],
			'blood_systolic' => ['required', 'numeric'],
			'blood_diastolic' => ['required', 'numeric'],
			'priority' => ['required', Rule::in(['0', '1'])],
		]);

		$patient = Patient::findOrFail($request->patient_id);

		if ($patient->existingQueue($request->clinic_id)->count() >= 1) {
			return response()->json(['message' => 'Patient already has an existing queue.'], Response::HTTP_OK);
		} else {
			$patientQueue = PatientQueue::create(array_merge([
				'patient_id' => $patient->id,
				'purpose' => $request->purpose,
				'doctor_id' => $request->doctor_id,
				'clinic_id' => $request->clinic_id,
				'type_service' => $request->type_service,
				'send_to' => 'doctor',
				'priority' => $request->priority,
				'status' => PatientQueueStatusEnum::Pending,
				'date_queue' => Carbon::now()->toDateString(),
				'room' => 'n/a',
			]));

			$vitalService->store($request, $patient->id);

			ClinicQueueEvent::dispatch($patientQueue->clinic_id);
			return response()->json([
				'data' => new PatientQueueResource($patientQueue->load('patient', 'doctor')),
				'message' => 'Patient successfully queued.'
			], Response::HTTP_CREATED);
		}
	}

	public function serve(PatientQueue $patientQueue)
	{
		$patientQueue->status = PatientQueueStatusEnum::Served;
		$patientQueue->save();

		ClinicQueueEvent::dispatch($patientQueue->clinic_id);



		return response()->json([
			'message' => 'Patient is served.'
		], Response::HTTP_OK);
	}

	public function cancel(PatientQueue $patientQueue)
	{
		$patientQueue->status = PatientQueueStatusEnum::Cancelled;
		$patientQueue->save();
		ClinicQueueEvent::dispatch($patientQueue->clinic_id);
		return response()->json([
			'message' => 'Patient queue is successfully cancelled.'
		], Response::HTTP_OK);
	}

	public function done(PatientQueue $patientQueue, Request $request)
	{
		$patientQueue->status = PatientQueueStatusEnum::Done;
		$patientQueue->diagnosis = $request->get('diagnosis');
		$patientQueue->save();

		ClinicQueueEvent::dispatch($patientQueue->clinic_id);
		return response()->json([
			'message' => 'Patient appointment is completed.'
		], Response::HTTP_OK);
	}

	public function destroy(ClinicService $clinicService, int $id)
	{
		$clinicService->clearQueues($id);

		return response()->noContent();
	}

	public function registeringList(Clinic $clinic)
	{
		return PatientQueueResource::collection(
			PatientQueue::where('status', PatientQueueStatusEnum::Pending)
				->where('clinic_id', $clinic->id)
				->orderBy('created_at')
				->get()
				->load('clinic', 'patient', 'doctor')
		);
	}

	public function inServiceList(Clinic $clinic)
	{
		return PatientQueueResource::collection(
			PatientQueue::where('status', PatientQueueStatusEnum::Served)
				->where('clinic_id', $clinic->id)
				->orderBy('created_at')
				->get()
				->load('clinic', 'patient', 'doctor')
		);
	}

	public function doneList(Clinic $clinic)
	{
		return PatientQueueResource::collection(
			PatientQueue::where('status', PatientQueueStatusEnum::Done)
				->where('clinic_id', $clinic->id)
				->latest()
				->get()
				->load('clinic', 'patient', 'doctor')
		);
	}

	public function patientQueueSummary($clinic_id)
	{
		$numberOfQueuedPatients = DB::table('patient_queues')->where('clinic_id', $clinic_id)->count();
		$totalClinicPersonnels = DB::table('clinic_personnels')->where('clinic_id', $clinic_id)->count();
		$totalDoctors = Doctor::query()
			->whereHas('user.assignClinic', function ($query) use ($clinic_id) {
				return $query->where('clinic_id', $clinic_id);
			})
			->count();

		return response()->json([
			"number_of_patients_in_queue" => $numberOfQueuedPatients,
			"total_clinic_personnels" => $totalClinicPersonnels,
			"total_doctors" => $totalDoctors
		]);
	}

	public function existingPatientQueue(Request $request)
	{
		if ($request->has('patient_id') && $request->has('clinic_id')) {
			$patient = Patient::find($request->patient_id);
			$patientQueue = $patient->existingQueue($request->clinic_id)->first();

			if ($patientQueue) {
				return response()->json([
					'data' => new PatientQueueResource($patientQueue->load('patient', 'doctor')),
					'message' => 'Patient queue data retrieved.'
				], Response::HTTP_OK);
			} else {
				return response()->json(['message' => 'Patient is not currently in a queue'], Response::HTTP_OK);
			}
		} else {
			return response()->json([
				'message' => 'Paramenters must contain patient_id and clinic_id.'
			], Response::HTTP_BAD_REQUEST);
		}
	}
}
