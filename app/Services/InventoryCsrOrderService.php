<?php

namespace App\Services;

use App\Models\InventoryCsr;
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
        return InventoryCSROrder::query()
            ->with(['patient', 'doctor', 'inventoryCsr'])
            ->findOrFail($id);
        // $inventoryCsrOrder = InventoryCSROrder::query()
        //     ->with(['patient', 'doctor', 'inventoryCsr'])
        //     ->findOrFail($id);

        // return $inventoryCsrOrder;
    }

    // public function getPatientCsrOrder($id)
    // {
    //     return InventoryCSROrder::where('id', $id)->get();
    // }

    public function getPatientCsrOrder($id)
    {
        return InventoryCSROrder::with(['patient', 'doctor', 'inventoryCsr'])
            ->where('patient_id', $id)
            ->get();
    }

    public function list(Request $request)
    {
        $inventoryCsrOrder = $request->get('inventory_csrs_id');

        return InventoryCSROrder::with(['patient', 'doctor', 'inventory_csrs'])
            ->when($inventoryCsrOrder, function ($query, $inventoryCsrOrder) {
                $query->where('inventory_csrs_id', $inventoryCsrOrder);
            })
            ->paginate($request->get('paginate', 12));
    }

    public function store(InventoryService $inventoryService, Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => ['required', 'string', Rule::exists('patients', 'id')],
            'inventory_csrs_id' => ['required', 'string', Rule::exists('inventory_csrs', 'id')],
            'doctor_id' => ['nullable'],
            'date' => ['nullable', 'date'],
            'quantity' => ['nullable', 'integer'],
        ]);

        $user = $request->user();

        $inventoryCsrOrder = InventoryCSROrder::create(array_merge([
            'patient_id' => $validatedData['patient_id'],
            'inventory_csrs_id' => $validatedData['inventory_csrs_id'] ?? null,
            'doctor_id' => $user->doctor_id,
            'date' => $validatedData['date'],
            'quantity' => $validatedData['quantity'],
        ]));

        // Update CSR supplies stock
        if (isset($validatedData['inventory_csrs_id']) && isset($validatedData['quantity'])) {
            $inventoryCsr = InventoryCsr::find($validatedData['inventory_csrs_id']);
            if ($inventoryCsr) {
                $inventoryCsr->csr_stocks -= $validatedData['quantity'];
                $inventoryCsr->save();
            }
        }

        $inventoryCsrOrder->load(['patient', 'doctor', 'inventoryCsr']);

        return $inventoryCsrOrder;
    }
    // Additional methods such as update and csrStocksIn can be implemented here.
}
