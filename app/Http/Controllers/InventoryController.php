<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemInventoryResource;
use App\Http\Resources\ItemResource;
use App\Models\HealthUnit;
use App\Models\Item;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{


    public function index(InventoryService $inventoryService)
    {

        $request_location_id = request()->get('location_id');

        $location =  $request_location_id ? HealthUnit::query()->findOrFail($request_location_id) : $this->getUserLocation();

        // $inventoryService->checkInitialInventory($location->id);

        $items = Item::query()
            ->with(['inventory' => fn ($q) => $q->where('location_id', request()->user()->health_unit_id)])
            ->paginate(request('paginate', 10));

        $inventoryService->checkInitialInventoryPerChunk($location->id, $items);

        return ItemResource::collection($items);
        // return ItemInventoryResource::collection($inventoryService->getInventoryList($location->id));
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

    public function addStock(InventoryService $inventoryService, Request $request, $id)
    {
        $location = $this->getUserLocation();
        $item = $inventoryService->stockIn($id, $location->id, $request->qty);

        return response()->json(['message' => "Success!", 'data' => ItemInventoryResource::make($item)]);
    }
    public function removeStock(InventoryService $inventoryService, Request $request, $id)
    {
        $location = $this->getUserLocation();
        $item = $inventoryService->stockOut($id, $location->id, $request->qty);

        return response()->json(['message' => "Success!", 'data' => ItemInventoryResource::make($item)]);
    }
}
