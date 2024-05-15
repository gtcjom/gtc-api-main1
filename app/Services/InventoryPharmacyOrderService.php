<?php

namespace App\Services;

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

    public function list(Request $request)
    {
        $inventoryPharmacyOrder = $request->get('supplies') ? $request->get('supplies') : null;

        return InventoryPharmacyOrder::query()
            ->with(['patient', 'doctor'])
            ->when($inventoryPharmacyOrder, function ($query, $inventoryPharmacyOrder) {
                $query->where('supplies', $inventoryPharmacyOrder);
            })
            ->paginate(request('paginate', 12));
    }

    public function store(InventoryService $inventoryService, Request $request)
    {
        $request->validate([
            'patient_id' => ['required', 'string', Rule::exists('patients', 'id')],
            'doctor_id' => ['nullable'],
            // 'inventory_csr_id' => ['nullable'],
            'date' => ['nullable', 'string'],
            'supplies' => ['nullable', 'string'],
            'quantity' => ['nullable', 'string'],
        ]);
        $user = $request->user();

        $inventoryService = InventoryPharmacyOrder::create(array_merge([
            'patient_id' => $request->patient_id,
            'doctor_id' => $user->doctor_id,
            // 'inventory_csr_id' => $request->inventory_csr_id,
            'date' => $request->date,
            'supplies' => $request->supplies,
            'quantity' => $request->quantity,
        ]));

        $inventoryService->load(['patient', 'doctor']);
        $inventoryService->save();
        return $inventoryService;
    }
    // public function update(Request $request, int $id): Model|InventoryCSROrder|Collection|Builder|array|null
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
