<?php

namespace App\Http\Controllers\PHO;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\LaboratoryItem;
use Illuminate\Http\Request;

class LaboratoryItemController extends Controller
{

    //create index method laboratory items
    public function index()
    {
        //return all laboratory items in resource LaboratoryItemResource
        $laboratoryItems = LaboratoryItem::query()
            ->joins('items', 'laboratory_items.item_uuid', '=', 'items.id')
            ->select(
                'laboratory_items.id',
                'laboratory_items.supplier',
                'laboratory_items.item_uuid',
                'items.name', 'items.description',
                'items.unit_measurement', 'items.type',
            )
            ->where('laboratory_items.management_id', request()->get('clinic_management_id'))
            ->get();

        return ItemResource::collection($laboratoryItems);

    }



    public function update(Request $request, string $clinic_management_id)
    {
        LaboratoryItem::query()
            ->where('management_id', $clinic_management_id)
            ->delete();

        $items = Item::query()->whereIn('id', $request->get('items') ?? [])->get();

        foreach ($items as $item) {
            LaboratoryItem::query()->create([
                'management_id' => $clinic_management_id,
                'item' => $item->name,
                'description' => $item->description,
                'unit' => $item->unit_measurement,
                'item_uuid' => $item->id,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

}
