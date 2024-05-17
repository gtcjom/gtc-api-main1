<?php

namespace App\Services;

use App\Models\InventoryPharmacy;
use App\Models\InventoryPharmacyOrder;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class InventoryPharmacyOrderService
{
    public function show(int $id)
    {
        $inventoryPharmacyOrder = InventoryPharmacyOrder::query()
            ->with(['patient', 'doctor'])
            ->findOrFail($id);

        return $inventoryPharmacyOrder;
    }
    // public function getPatientPharmacyOrders($id)
    // {
    //     return InventoryPharmacyOrder::where('id', $id)->get();
    // }
    public function getPatientPharmacyOrder($id)
    {
        return InventoryPharmacyOrder::with(['patient', 'doctor', 'inventoryPharmacy'])
            ->where('patient_id', $id)
            ->get();
    }


    public function list(Request $request)
    {
        $inventoryPharmacyOrder = $request->get('inventory_pharmacies_id') ? $request->get('supplies') : null;

        return InventoryPharmacyOrder::query()
            ->with(['patient', 'doctor', 'inventory_pharmacies'])
            ->when($inventoryPharmacyOrder, function ($query, $inventoryPharmacyOrder) {
                $query->where('inventory_pharmacies_id', $inventoryPharmacyOrder);
            })
            ->paginate(request('paginate', 12));
    }

    public function store(InventoryService $inventoryService, Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => ['required', 'string', Rule::exists('patients', 'id')],
            'inventory_pharmacies_id' => ['required', 'string', Rule::exists('inventory_pharmacies', 'id')],
            'doctor_id' => ['nullable'],
            'date' => ['nullable', 'date'],
            'quantity' => ['nullable', 'integer'],
        ]);

        $user = $request->user();

        $inventoryPharmacyOrder = InventoryPharmacyOrder::create(array_merge([
            'patient_id' => $validatedData['patient_id'],
            'inventory_pharmacies_id' => $validatedData['inventory_pharmacies_id'] ?? null,
            'doctor_id' => $user->doctor_id,
            'date' => $validatedData['date'],
            'quantity' => $validatedData['quantity'],
        ]));

        if (isset($validatedData['inventory_pharmacies_id']) && isset($validatedData['quantity'])) {
            $inventoryPharmacy = InventoryPharmacy::find($validatedData['inventory_pharmacies_id']);
            if ($inventoryPharmacy) {
                $inventoryPharmacy->pharmacy_stocks -= $validatedData['quantity'];
                $inventoryPharmacy->save();
            }
        }

        $inventoryPharmacyOrder->load(['patient', 'doctor', 'inventoryPharmacy']);

        return $inventoryPharmacyOrder;
    }
    // public function update(Request $request, int $id): Model|InventoryPharmaciesOrder|Collection|Builder|array|null
    // {
    //     $data = $request->validate([
    //         'operation_status' => ['required', 'string'],
    //     ]);

    //     $operationProcedure = OperationProcedure::findOrFail($id);
    //     $operationProcedure->update($data);
    //     // $operationProcedure->operation_status = $data['operation_status'];
    //     // $operationProcedure->save();
    //     $operationProcedure->load(['patient', 'doctor', 'clinic']);

    //     return $operationProcedure;
    // }
}
