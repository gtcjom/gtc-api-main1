<?php

namespace App\Http\Controllers\V2\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentDataResource;
use App\Http\Resources\LaboratoryOrderResource;
use App\Models\AppointmentData;
use App\Models\LaboratoryOrder;
use App\Models\LaboratoryTest;
use App\Services\InventoryService;
use App\Services\LaboratoryOrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LaboratoryOrderController extends Controller
{
	public function index(Request $request, LaboratoryOrderService $laboratoryOrderService)
	{
		return LaboratoryOrderResource::collection($laboratoryOrderService->list($request));
	}

	public function patientLabOrders($id)
	{
		$laboratory_test_type = request()->get('laboratory_test_type') ? request()->get('laboratory_test_type') : null;
		$type_ids = false;
		if ($laboratory_test_type) {
			$type_ids = LaboratoryTest::query()->where('type', $laboratory_test_type == 1 ? 'imaging' : 'laboratory-test')->pluck('id');
		}
		if (request()->get('laboratory_test_type') == 'all') {
			$type_ids = LaboratoryTest::query()->pluck('id');
		}
		return LaboratoryOrderResource::collection(LaboratoryOrder::where('patient_id', $id)
			->when(request('order_id') && request('order_id')  != 'undefined', function ($q) {
				return $q->where('id', request('order_id'));
			})
			->when($type_ids, function ($query, $type_ids) {
				$query->whereIn('laboratory_test_type', $type_ids);
			})
			->when(request('appointment_id'), function ($q) {
				return $q->where('appointment_id', request('appointment_id'));
			})
			->latest()
			->get()
			->load('patient', 'doctor', 'clinic', 'healthUnit'));
	}

	public function getLaboratoryQueue()
	{
		$user = request()->user();
		$type = ($user->type == 'RHU-XRAY' || $user->type == 'SPH-XRAY') ? 'imaging' : 'laboratory-test';
		$lab_test_ids = LaboratoryTest::query()->where('type', $type)->pluck('id');

		$pending_tests = LaboratoryOrder::query()
			->where('order_status', request('status', 'pending'))
			->where('health_unit_id', $user->health_unit_id)
			->whereIn('laboratory_test_type', $lab_test_ids)
			->with(['patient', 'doctor', 'clinic', 'healthUnit'])
			->latest()
			->paginate(request('paginate', 10));
		return LaboratoryOrderResource::collection($pending_tests);
	}

	public function sendPatientToLab($appointment_id)
	{
		$appointment = AppointmentData::query()->findOrFail($appointment_id);

		$labOrders = LaboratoryOrder::query()
			->where('order_status', 'pending')
			->where('patient_id', $appointment->patient_id)
			->get();
		if ($labOrders->count()) {
			$appointment->status = 'pending-for-lab-result';
			$appointment->save();
		}
		return [
			'pending_lab_orders' => $labOrders,
			// 'pending_lab_orders' => LaboratoryOrderResource::make($labOrders),
			'apppointment' => AppointmentDataResource::make($appointment)
		];

		// foreach ($labOrders as $labOrder) {
		// 	$order = LaboratoryOrder::query()->findOrFail($labOrder->id);
		// 	$order->order_status = 'pending';
		// 	$order->save();
		// }
	}

	public function uploadLabResult($id)
	{
		$labOrder = LaboratoryOrder::query()->findOrFail($id);

		if (request()->hasFile('attachment')) {
			$labOrder->attachment = request()->file('attachment')->store('users/avatar');
		}
		$labOrder->order_status = 'for-result-reading';
		$labOrder->lab_result_notes = request('lab_result_notes');
		$labOrder->processed_by = request()->user()->id;
		$labOrder->save();


		// $appointment = AppointmentData::query()->where('patient_id', $labOrder->patient_id)->where('status', 'pending-for-lab-result')->first();
		// $appointment->status = 'for-result-reading';
		// $appointment->save();

		return LaboratoryOrderResource::make($labOrder);
	}

	public function store(Request $request, InventoryService $inventoryService, LaboratoryOrderService $laboratoryOrderService)
	{
		return response()->json([
			'data' => new LaboratoryOrderResource($laboratoryOrderService->store($inventoryService, $request)),
			'message' => 'Laboratory order created successfully.'
		], Response::HTTP_CREATED);
	}

	public function update(Request $request, LaboratoryOrderService $laboratoryOrderService, int $id)
	{
		return response()->json([
			'data' => new LaboratoryOrderResource($laboratoryOrderService->update($request, $id)),
			'message' => 'Laboratory order updated successfully.'
		], Response::HTTP_OK);
	}

	public function show(LaboratoryOrderService $laboratoryOrderService, int $id)
	{
		return response()->json([
			'data' => new LaboratoryOrderResource($laboratoryOrderService->show($id)),
			'message' => 'Laboratory order retrived successfully.'
		], Response::HTTP_OK);
	}

	public function acceptLaboratoryOrder(Request $request, LaboratoryOrder $laboratoryOrder)
	{
		// accept laboratory order
		$laboratoryOrder->update(array_merge([
			'accepted_by' => $request->user()->id,
			'accepted_at' => Carbon::now()
		]));

		return response()->json([
			'data' => new LaboratoryOrderResource($laboratoryOrder),
			'message' => 'Laboratory order is successfully accepted.'
		], Response::HTTP_OK);
	}

	public function destroy($id)
	{
		//
		$order =  LaboratoryOrder::query()->findOrFail($id);
		$order->delete();
		return response()->json(['message' => "Deleted successfully!"], 200);
	}
}
