<?php

namespace App\Services;

use App\Models\InventoryCsr;
use App\Models\Item;
use App\Models\ItemInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;
use Illuminate\Http\Response;

class InventoryAnesthesiaService
{

    public function getInventoryList($location_id)
    {
        return InventoryCsr::query()
            ->where('location_id', $location_id)
            ->paginate(request('paginate', 10));
    }
    public function checkInitialInventory($location_id)
    {
        $items = Item::query()->get();
        foreach ($items as $item) {
            $this->getItemInventoryInstance($item->id, $location_id);
        }
    }
    public function checkInitialInventoryPerChunk($location_id, $items)
    {
        // $items = Item::query()->get();
        foreach ($items as $item) {
            $this->getItemInventoryInstance($item->id, $location_id);
        }
    }
    public function getItemInventoryInstance($item_id, $location_id)
    {
        // try {
        //     DB::beginTransaction();

        $item = Item::query()->findOrFail($item_id);
        if ($item->id) {

            $inventory = ItemInventory::query()
                ->where('item_id', $item_id)
                ->where('location_id', $location_id)
                ->first();
            if ($inventory) {
                return $inventory;
            } else {
                $inventory = new ItemInventory();
                $inventory->location_id = $location_id;
                $inventory->item_id = $item_id;
                $inventory->save();
                return $inventory;
            }
        }
        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollBack();
        // }
    }

    public function stockIn($item_id, $location_id, $qty, $price = 0.00)
    {
        try {
            DB::beginTransaction();

            $inventory = $this->getItemInventoryInstance($item_id, $location_id);
            $inventory->quantity =  $inventory->quantity + $qty;
            if ($price)
                $inventory->price =  $price;
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
