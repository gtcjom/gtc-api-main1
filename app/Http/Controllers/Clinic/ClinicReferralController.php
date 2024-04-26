<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicReferralRequest;
use App\Http\Requests\StoreClinicReferralRequest;
use App\Http\Requests\UpdateClinicReferralRequest;
use App\Http\Resources\ClinicReferralResource;
use App\Models\ClinicReferral;
use App\Services\ClinicReferralService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClinicReferralController extends Controller
{

    public function store(StoreClinicReferralRequest $request, ClinicReferralService $clinicReferralService)
    {
		return response()->json([
			'data' => ClinicReferralResource::make($clinicReferralService->store($request)),
			'message' => 'Client referral created successfully.'
		], Response::HTTP_CREATED);
    }

    public function show(int $clinicReferral, ClinicReferralService $clinicReferralService)
    {
        return response()->json([
			'data' => ClinicReferralResource::make($clinicReferralService->show($clinicReferral)),
			'message' => 'Client referral retrieved successfully.'
		], Response::HTTP_OK);
    }

    public function update(UpdateClinicReferralRequest $request, ClinicReferralService $clinicReferralService, int $id)
    {
		return response()->json([
			'data' => ClinicReferralResource::make($clinicReferralService->update($request, $id)),
			'message' => 'Client referral updated successfully.'
		], Response::HTTP_OK);
    }

    public function destroy( ClinicReferralService $clinicReferralService, int $id)
    {
        $clinicReferralService->delete($id);

		return response()->json(['message' => 'Client referral deleted successfully.'], Response::HTTP_OK);
    }

	public function myReferrals(ClinicReferralService $clinicReferralService, int $clinic_id)
	{
		return response()->json([
			'data' => ClinicReferralResource::collection($clinicReferralService->myReferrals($clinic_id)),
			'message' => 'Clinic referrals retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function receivedReferrals(ClinicReferralService $clinicReferralService, int $clinic_id)
	{
		return response()->json([
			'data' => ClinicReferralResource::collection($clinicReferralService->receivedReferrals($clinic_id)),
			'message' => 'Received referrals retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function list(ClinicReferralService $clinicReferralService, Request $request)
	{
		return response()->json([
			'data' => ClinicReferralResource::collection($clinicReferralService->list($request)),
			'message' => 'List of clinic referrals retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function receive(ClinicReferralService $clinicReferralService, Request $request, int $id)
	{
		return response()->json([
			'data' => ClinicReferralResource::make($clinicReferralService->receive($request, $id)),
			'message' => 'Clinic referral was received successfully.'
		], Response::HTTP_OK);
	}

	public function serve(ClinicReferralService $clinicReferralService, int $id)
	{
		return response()->json([
			'data' => ClinicReferralResource::make($clinicReferralService->serve($id)),
			'message' => 'Clinic referral was successfully served.',
		], Response::HTTP_OK);
	}

	public function done(ClinicReferralService $clinicReferralService, int $id)
	{
		return response()->json([
			'data' => ClinicReferralResource::make($clinicReferralService->done($id)),
			'message' => 'Clinic referral was successfully completed.'
		], Response::HTTP_OK);
	}

	public function cancel(ClinicReferralService $clinicReferralService, int $id)
	{
		return response()->json([
			'data' => ClinicReferralResource::make($clinicReferralService->cancel($id)),
			'message' => 'Clinic referral was cancelled.'
		], Response::HTTP_OK);
	}
}
