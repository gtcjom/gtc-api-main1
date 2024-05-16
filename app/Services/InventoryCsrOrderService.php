<?php

namespace App\Services;

use App\Models\InventoryCSROrder;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class InventoryCsrOrderService
{
    public function show(int $id)
    {
        $inventoryCsrOrder = InventoryCSROrder::query()
            ->with(['patient', 'doctor'])
            ->findOrFail($id);

        return $inventoryCsrOrder;
    }
    public function getPatientCsrOrders($patientId)
    {
        return InventoryCSROrder::where('patient_id', $patientId)->get();
    }

    public function list(Request $request)
    {
        $inventoryCsrOrder = $request->get('supplies') ? $request->get('supplies') : null;

        return InventoryCSROrder::query()
            ->with(['patient', 'doctor'])
            ->when($inventoryCsrOrder, function ($query, $inventoryCsrOrder) {
                $query->where('supplies', $inventoryCsrOrder);
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

        $inventoryService = InventoryCSROrder::create(array_merge([
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

    // public function getInventoryCSRInstance()
    // {

    // }
}
