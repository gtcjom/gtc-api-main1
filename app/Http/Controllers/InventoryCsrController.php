<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryCsrResource;
use App\Models\InventoryCsr;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class InventoryCsrController extends Controller
{
    public function index()
    {
        $stocks = InventoryCsr::query()
            ->when(request('column') && request('direction'), function ($q) {
                return $q->orderBy(request('column'), request('direction'));
            })
            ->when(request('type'), function ($q) {
                return $q->where('type', request('type'));
            })
            ->when(request('keyword'), function (Builder $q) {
                $keyword = request('keyword');
                return $q->whereRaw("CONCAT_WS(' ', firstname, middle, lastname) LIKE ?", ["%{$keyword}%"]);
            })
            ->orderBy('id', 'desc')
            ->paginate(request('paginate', 12));

        return InventoryCsrResource::collection($stocks);
    }

    public function getSupplies()
    {
        $supplies = InventoryCsr::getSupplies();
        return response()->json($supplies);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'csr_date' => ['required', 'date'],
            'csr_supplies' => ['required', 'string'],
            'csr_stocks' => ['required', 'integer'],
            'csr_price' => ['nullable', 'numeric'],
            'csr_status' => ['nullable', 'string'],
        ]);

        $stocks = InventoryCsr::create($validatedData);

        return InventoryCsrResource::make($stocks);
    }

    public function show(int $id)
    {
        $stocks = InventoryCsr::findOrFail($id);
        return InventoryCsrResource::make($stocks);
    }

    public function update(Request $request, int $id)
    {
        $stocks = InventoryCsr::findOrFail($id);

        $validatedData = $request->validate([
            'csr_date' => ['sometimes', 'date'],
            'csr_supplies' => ['sometimes', 'string'],
            'csr_stocks' => ['sometimes', 'integer'],
            'csr_price' => ['sometimes', 'numeric'],
            'csr_status' => ['sometimes', 'string'],
        ]);

        $stocks->update($validatedData);

        return InventoryCsrResource::make($stocks);
    }

    public function destroy(int $id)
    {
        $stocks = InventoryCsr::findOrFail($id);
        $stocks->delete();

        return response()->json(['message' => "Deleted successfully!"], 200);
    }
}
