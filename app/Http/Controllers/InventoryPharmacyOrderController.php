<?php

namespace App\Http\Controllers;

use App\Models\InventoryPharmacyOrder;
use App\Http\Requests\StoreInventoryPharmacyOrderRequest;
use App\Http\Requests\UpdateInventoryPharmacyOrderRequest;
use App\Http\Resources\InventoryPharmacyOrderResource;
use App\Http\Resources\InventoryPharmacyResource;
use App\Services\InventoryPharmacyOrderService;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InventoryPharmacyOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, InventoryPharmacyOrderService $inventorypharmacyOrderService)
    {
        //
        return InventoryPharmacyResource::collection($inventorypharmacyOrderService->list($request));
    }

    public function getPatientPharmacyOrders($id, InventoryPharmacyOrderService $inventoryPharmacyOrderService)
    {
        // Fetch patient CSR orders using the service
        $patientPharmacyOrders = $inventoryPharmacyOrderService->getPatientPharmacyOrders($id);

        // Return the orders in a JSON response
        return response()->json([
            'data' => InventoryPharmacyOrderResource::collection($patientPharmacyOrders),
            'message' => 'Patient Pharmacy Orders retrieved successfully.'
        ], Response::HTTP_OK);
    }
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
     * @param  \App\Http\Requests\StoreInventoryPharmacyOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, InventoryService $inventoryService, InventoryPharmacyOrderService $inventoryPharmacyOrderService)
    {
        //
        return response()->json([
            'data' => new InventoryPharmacyOrderResource($inventoryPharmacyOrderService->store($inventoryService, $request)),
            'message' => 'Pharmacy Addedd Successfully.'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryPharmacyOrder  $inventoryPharmacyOrder
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryPharmacyOrderService $inventoryPharmacyOrderService, int $id)
    {
        //
        return response()->json([
            'data' => new InventoryPharmacyOrderResource($inventoryPharmacyOrderService->show($id)),
            'message' => 'Inventory Pharmacy retrived successfully.'
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryPharmacyOrder  $inventoryPharmacyOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryPharmacyOrder $inventoryPharmacyOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInventoryPharmacyOrderRequest  $request
     * @param  \App\Models\InventoryPharmacyOrder  $inventoryPharmacyOrder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInventoryPharmacyOrderRequest $request, InventoryPharmacyOrder $inventoryPharmacyOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryPharmacyOrder  $inventoryPharmacyOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryPharmacyOrder $inventoryPharmacyOrder)
    {
        //
    }
}
