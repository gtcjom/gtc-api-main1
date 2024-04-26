<?php

namespace App\Services;

use App\Http\Resources\ItemInventoryResource;
use App\Models\HealthUnit;
use App\Models\LaboratoryOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class LaboratoryOrderService
{
	public function show(int $id)
	{
		$laboratoryOrder = LaboratoryOrder::query()
			->with(['patient', 'doctor', 'clinic', 'healthUnit'])
			->findOrFail($id);

		return $laboratoryOrder;
	}

	public function list(Request $request)
	{
		$laboratory_test_type = $request->get('laboratory_test_type') ? $request->get('laboratory_test_type') : null;

		return LaboratoryOrder::query()
			->with(['patient', 'doctor', 'clinic', 'healthUnit'])
			->when($laboratory_test_type, function ($query, $laboratory_test_type) {
				$query->where('laboratory_test_type', $laboratory_test_type);
			})
			->paginate(request('paginate', 12));
	}

	public function store(InventoryService $inventoryService, Request $request)
	{
		$request->validate([
			'order_number' => ['nullable'],
			'order_date' => ['required', 'date'],
			'patient_id' => ['required', 'string', Rule::exists('patients', 'id')],
			'laboratory_test_type' => ['required', 'string'],
			'doctor_id' => ['nullable'],
			'clinic_id' => ['nullable'],
			'appointment_id' => ['nullable'],
			'notes' => ['required', 'nullable'],
		]);
		$user = $request->user();
		// if (request()->hasFile('avatar')) {
		// 	$user->avatar = request()->file('avatar')->store('users/avatar');
		// }
		$laboratoryOrder = LaboratoryOrder::create(array_merge([
			'order_number' => 'LAB-' . substr(strtoupper(bin2hex(random_bytes(6))), 0, -1),
			'order_date' => $request->order_date,
			'patient_id' => $request->patient_id,
			'appointment_id' => $request->appointment_id,
			'health_unit_id' => $user->health_unit_id,
			'doctor_id' => $user->id,
			'laboratory_test_type' => $request->laboratory_test_type,
			// 'clinic_id' => $request?->clinic_id ?: $this->getUserLocation()->id,
			'notes' => $request->notes,
			'order_status' => 'pending'
		]));

		// foreach ($request->items as $itemId) {
		// 	$this->removeStock($inventoryService, $itemId);
		// }

		$laboratoryOrder->load(['patient', 'doctor', 'clinic', 'healthUnit']);

		return $laboratoryOrder;
	}


	public function removeStock(InventoryService $inventoryService, $id)
	{
		$location = $this->getUserLocation();
		$item = $inventoryService->stockOut($location->id, $id,  1);
		// return $item;
		return response()->json(['message' => "Success!", 'data' => ItemInventoryResource::make($item)]);
	}

	public function getUserLocation()
	{
		$user = request()->user();
		$location = HealthUnit::first();

		if (str_contains($user->type, 'RHU')) {
			$location = HealthUnit::query()->where('type', '=', 'RHU')->where('municipality_id', '=', $user->municipality)->first();
		}
		if ($user->type == 'LMIS-BHS' || $user->type == 'BHS-BHW') {
			$location = HealthUnit::query()->where('type', '=', 'BHS')->where('barangay_id', '=', $user->barangay)->first();
		}
		if ($user->type == 'LMIS-CNOR') {
			$location = HealthUnit::query()->where('type', 'CNOR')->first();
		}
		return $location;
	}
	public function update(Request $request, int $id): Model|LaboratoryOrder|Collection|Builder|array|null
	{
		$data = $request->validate([
			'order_date' => ['required', 'date'],
			'laboratory_test_type' => ['required', 'string'],
			'notes' => ['required', 'nullable'],
		]);

		$laboratoryOrder = LaboratoryOrder::findOrFail($id);
		$laboratoryOrder->update(array_merge($data));
		$laboratoryOrder->load(['patient', 'doctor', 'clinic']);

		return $laboratoryOrder;
	}
}
