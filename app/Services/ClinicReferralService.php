<?php

namespace App\Services;

use App\Enums\ClinicReferralStatusEnum;
use App\Models\ClinicReferral;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ClinicReferralService{

	public function show($id)
    {
		$clinicReferral = ClinicReferral::query()
            ->with(['patient', 'fromClinic', 'toClinic', 'addedBy', 'receivedBy'])
            ->findOrFail($id);

		return $clinicReferral;
    }

	public function store(Request $request)
	{
		$clinicReferral = new ClinicReferral();
		$clinicReferral->patient_id = $request->get('patient_id');
		$clinicReferral->referral_date = $request->get('referral_date');
		$clinicReferral->added_by = $request->user()->id;
		$clinicReferral->notes = $request->get('notes');
		$clinicReferral->from_clinic_id = $request->get('from_clinic_id');
		$clinicReferral->to_clinic_id = $request->get('to_clinic_id');
		$clinicReferral->diagnosis = $request->get('diagnosis');
		$clinicReferral->status = "pending";

		$clinicReferral->save();
		$clinicReferral->load(['patient', 'fromClinic', 'toClinic', 'addedBy']);

		return $clinicReferral;
	}

	public function update($request, int $id): Model|ClinicReferral|Collection|Builder|array|null
	{
		$clinicReferral = ClinicReferral::findOrFail($id);
		$clinicReferral->notes = $request->get('notes');
		$clinicReferral->referral_date = $request->get('referral_date');
		$clinicReferral->to_clinic_id = $request->get('to_clinic_id');
		$clinicReferral->diagnosis = $request->get('diagnosis');

		$clinicReferral->save();
		$clinicReferral->load(['patient', 'fromClinic', 'toClinic', 'addedBy', 'receivedBy']);

		return $clinicReferral;
	}

	public function delete(int $id): Model|Collection|Builder|array|null
	{
		$clinic = ClinicReferral::query()->findOrFail($id);
        $clinic->delete();

        return $clinic;
	}

	public function myReferrals(int $clinic_id)
	{
		return ClinicReferral::query()
			->with(['patient', 'fromClinic', 'toClinic', 'addedBy', 'receivedBy'])
			->where('from_clinic_id', $clinic_id)
			->latest()
			->paginate(request('paginate', 12));
	}

	public function receivedReferrals(int $clinic_id)
	{
		return ClinicReferral::query()
			->with(['patient', 'fromClinic', 'toClinic', 'addedBy', 'receivedBy'])
			->where('to_clinic_id', $clinic_id)
			->latest()
			->paginate(request('paginate', 12));
	}

	public function list(Request $request)
	{
		$status = $request->get('status') ? $request->get('status') : null;
		$from_clinic_id = $request->get('from_clinic_id') ? $request->get('from_clinic_id') : null;
		$to_clinic_id = $request->get('to_clinic_id') ? $request->get('to_clinic_id') : null;
		$date_from = $request->get('date_from') ? $request->get('date_from') : null;
		$date_to = $request->get('date_to') ? $request->get('date_to') : null;

		return ClinicReferral::query()
			->with(['patient', 'fromClinic', 'toClinic', 'addedBy', 'receivedBy'])
			->when($status, function($query, $status){
				$query->where('status', $status);
			})
			->when($from_clinic_id, function($query, $from_clinic_id){
				$query->where('from_clinic_id', $from_clinic_id);
			})
			->when($to_clinic_id, function($query, $to_clinic_id){
				$query->where('to_clinic_id', $to_clinic_id);
			})
			->when($date_from, function($query, $date_from){
				$query->where('referral_date', '>=', $date_from);
			})
			->when($date_to, function($query, $date_to){
				$query->where('referral_date', '<=', $date_to);
			})
			->latest()
			->get();
	}

	public function receive($request, $id): Model|ClinicReferral|Collection|Builder|array|null
	{
		$clinicReferral = ClinicReferral::findOrFail($id);
		$clinicReferral->received_by = $request->user()->id;
		$clinicReferral->date_received = Carbon::now();

		$clinicReferral->save();
		$clinicReferral->load(['patient', 'fromClinic', 'toClinic', 'addedBy', 'receivedBy']);

		return $clinicReferral;
	}

	public function serve($id): Model|ClinicReferral|Collection|Builder|array|null
	{
		$clinicReferral = ClinicReferral::findOrFail($id);
		$clinicReferral->date_served = Carbon::now();
		$clinicReferral->status = ClinicReferralStatusEnum::Served;

		$clinicReferral->save();
		$clinicReferral->load(['patient', 'fromClinic', 'toClinic', 'addedBy', 'receivedBy']);

		return $clinicReferral;
	}

	public function done($id): Model|ClinicReferral|Collection|Builder|array|null
	{
		$clinicReferral = ClinicReferral::findOrFail($id);
		$clinicReferral->date_completed = Carbon::now();
		$clinicReferral->status = ClinicReferralStatusEnum::Done;

		$clinicReferral->save();
		$clinicReferral->load(['patient', 'fromClinic', 'toClinic', 'addedBy', 'receivedBy']);

		return $clinicReferral;
	}

	public function cancel($id): Model|ClinicReferral|Collection|Builder|array|null
	{
		$clinicReferral = ClinicReferral::findOrFail($id);
		$clinicReferral->status = ClinicReferralStatusEnum::Cancelled;

		$clinicReferral->save();
		$clinicReferral->load(['patient', 'fromClinic', 'toClinic', 'addedBy', 'receivedBy']);

		return $clinicReferral;
	}
}