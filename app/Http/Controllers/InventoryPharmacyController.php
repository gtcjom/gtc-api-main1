<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryPharmacyOrderResource;
use App\Http\Resources\InventoryPharmacyResource;
use App\Models\InventoryPharmacy;
use App\Services\InventoryPharmacyOrderService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class InventoryPharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $stocks = InventoryPharmacy::query()->when(
            request('column') && request('direction'),
            fn ($q) => $q->orderBy(request('column'), request('direction'))
        )->when(
            request('type'),
            fn ($q) => $q->where('type', request('type'))
        )
            ->when(
                request('keyword'),
                function (Builder $q) {
                    $keyword = request('keyword');
                    return $q->whereRaw("CONCAT_WS(' ', firstname, middle,lastname) like '%{$keyword}%' ");
                }
            )
            ->orderBy('id', 'desc')
            ->paginate(request('paginate', 12));
        return InventoryPharmacyResource::collection($stocks);
    }
    // public function getPatientPharmacyOrders($id, InventoryPharmacyOrderService $inventoryPharmacyOrderService)
    // {
    //     // Fetch patient Pharmacy orders using the service
    //     $patientPharmacyOrders = $inventoryPharmacyOrderService->getPatientPharmacyOrders($id);

    //     // Return the orders in a JSON response
    //     return response()->json([
    //         'data' => InventoryPharmacyOrderResource::collection($patientPharmacyOrders),
    //         'message' => 'Patient Pharmacy Orders retrieved successfully.'
    //     ], Response::HTTP_OK);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
        $stocks = new InventoryPharmacy();
        $stocks->pharmacy_date = request()->get('pharmacy_date');
        $stocks->pharmacy_supplies = request()->get('pharmacy_supplies');
        $stocks->pharmacy_stocks = request()->get('pharmacy_stocks');
        $stocks->pharmacy_price = request()->get('pharmacy_price');
        $stocks->pharmacy_status = request()->get('pharmacy_status');
        $stocks->save();
        return InventoryPharmacyResource::make($stocks);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryPharmacy  $inventoryPharmacy
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
        $stocks = InventoryPharmacy::query()->findOrFail($id);
        return InventoryPharmacyResource::make($stocks);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryPharmacy  $inventoryPharmacy
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryPharmacy $inventoryPharmacy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryPharmacy  $inventoryPharmacy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryPharmacy $inventoryPharmacy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryPharmacy  $inventoryPharmacy
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
        $stocks = InventoryPharmacy::query()->findOrFail($id);
        $stocks->delete();
        return response()->json(['message' => "Deleted successfully!"], 200);
    }
}
