<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryCsrResource;
use App\Models\InventoryCsr;
use App\Models\InventoryStocks;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class InventoryCsrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $stocks = InventoryCsr::query()->when(
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
        return InventoryCsrResource::collection($stocks);
    }

    // public function getCsrSupplies()
    // {
    //     $supplies = InventoryCsr::pluck('csr_supplies');
    //     return response()->json($supplies);
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

    public function getCsrSupplies()
    {
        $supplies = InventoryCsr::all();
        return InventoryCsrResource::collection($supplies);
    }

    public function store()
    {
        //
        $stocks = new InventoryCsr();
        $stocks->csr_date = request()->get('csr_date');
        $stocks->csr_supplies = request()->get('csr_supplies');
        $stocks->csr_stocks = request()->get('csr_stocks');
        $stocks->csr_price = request()->get('csr_price');
        $stocks->csr_status = request()->get('csr_status');
        $stocks->save();
        return InventoryCsrResource::make($stocks);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryCsr  $inventoryCsr
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
        $stocks = InventoryCsr::query()->findOrFail($id);
        return InventoryCsrResource::make($stocks);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryCsr  $inventoryCsr
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryCsr $inventoryCsr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryCsr  $inventoryCsr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryCsr $inventoryCsr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryCsr  $inventoryCsr
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
        $stocks = InventoryCsr::query()->findOrFail($id);
        $stocks->delete();
        return response()->json(['message' => "Deleted successfully!"], 200);
    }
}
