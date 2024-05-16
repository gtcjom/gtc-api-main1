<?php

namespace App\Http\Controllers;

use App\Models\InventoryCSROrder;
use App\Http\Requests\StoreInventoryCSROrderRequest;
use App\Http\Requests\UpdateInventoryCSROrderRequest;
use App\Http\Resources\InventoryCSROrderResource;
use App\Services\InventoryCsrOrderService;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InventoryCSROrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, InventoryCsrOrderService $inventoryCsrOrderService)
    {
        //
        return InventoryCSROrderResource::collection($inventoryCsrOrderService->list($request));
    }


    public function getPatientCsrOrders($id, InventoryCsrOrderService $inventoryCsrOrderService)
    {
        // Fetch patient CSR orders using the service
        $patientCsrOrders = $inventoryCsrOrderService->getPatientCsrOrders($id);

        // Return the orders in a JSON response
        return response()->json([
            'data' => InventoryCSROrderResource::collection($patientCsrOrders),
            'message' => 'Patient CSR Orders retrieved successfully.'
        ], Response::HTTP_OK);
    }


    // public function patientCsrOrder($id)
    // {
    //     return InventoryCSROrder::where('patient_id', $id)->get();
    // }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInventoryCSROrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, InventoryService $inventoryService, InventoryCsrOrderService $inventoryCsrOrderService)
    {
        //
        return response()->json([
            'data' => new InventoryCSROrderResource($inventoryCsrOrderService->store($inventoryService, $request)),
            'message' => 'CSR Addedd Successfully.'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryCSROrder  $inventoryCSROrder
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryCsrOrderService $inventoryCsrOrderService, int $id)
    {
        //
        return response()->json([
            'data' => new InventoryCSROrderResource($inventoryCsrOrderService->show($id)),
            'message' => 'Inventory CSR retrived successfully.'
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryCSROrder  $inventoryCSROrder
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryCSROrder $inventoryCSROrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInventoryCSROrderRequest  $request
     * @param  \App\Models\InventoryCSROrder  $inventoryCSROrder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInventoryCSROrderRequest $request, InventoryCSROrder $inventoryCSROrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryCSROrder  $inventoryCSROrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryCSROrder $inventoryCSROrder)
    {
        //
    }
}
