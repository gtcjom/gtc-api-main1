<?php

namespace App\Services;

use App\Models\HealthUnit;
use App\Models\ItemInventory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;
use Illuminate\Http\Response;

class HealthUnitService
{

    public function getHealthUnitList()
    {
        return HealthUnit::query()
            ->when(request('municipality_id'), function ($query,) {
                return $query->where('municipality_id', '=', request()->municipality_id);
            })
            ->when(request('barangay_id'), function ($query,) {
                return $query->where('barangay_id', '=', request()->barangay_id);
            })
            ->when(request('type'), function ($query, $request) {
                return $query->where('type', '=', request()->type);
            })
            ->when(
                request('keyword'),
                function (Builder $q) {
                    $keyword = request('keyword');
                    return $q->whereRaw("CONCAT_WS(' ',name,status) like '%{$keyword}%' ");
                }
            )
            ->whereNot('id', 2)
            ->paginate(request()->get('paginate', 10));
    }

    public function getItemInventoryInstance($item_id, $location_id)
    {
        try {
            DB::beginTransaction();
            $inventory = ItemInventory::firstOrNew([
                'location_id' => $location_id,
                'item_id' => $item_id
            ]);
            DB::commit();
            return  $inventory;
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function stockIn($item_id, $location_id, $qty)
    {
        try {
            DB::beginTransaction();

            $inventory = $this->getItemInventoryInstance($item_id, $location_id);
            $inventory->quantity =  $inventory->quantity + $qty;
            $inventory->save();

            DB::commit();
            return $inventory;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Failed!"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function stockOut($location_id, $item_id, $qty)
    {
        try {
            DB::beginTransaction();

            $inventory = $this->getItemInventoryInstance($item_id, $location_id);
            $inventory->quantity =  $inventory->quantity - $qty;
            $inventory->save();

            DB::commit();
            return $inventory;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Failed!"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
