<?php

namespace App\Http\Controllers\PHO;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function index()
    {
        //return all items in resource ItemResource
        return ItemResource::collection(Item::query()->paginate(10));
    }

    public function store(Request $request)
    {
        //validate items name, description, unit_measurement, type
        $request->validate([
            'code' => ['nullable', 'string', 'max:150'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:200'],
            'unit_measurement' => ['required', 'string', 'max:50'],
            'type' => ['required', 'string', 'max:50'],
        ]);
        //create new an item and return the item in resource ItemResource
        return ItemResource::make(Item::query()->create($request->only([
            'code',
            'name',
            'description',
            'unit_measurement',
            'type',
        ])));
    }

    //creat updated method for item
    public function update(Request $request, Item $item)
    {
        //validate items name, description, unit_measurement, type
        $request->validate([
            'code' => ['nullable', 'string', 'max:150'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:200'],
            'unit_measurement' => ['required', 'string', 'max:50'],
            'type' => ['required', 'string', 'max:50'],
        ]);
        //update the item and return the item in resource ItemResource
        $item->update($request->only([
            'code',
            'name',
            'description',
            'unit_measurement',
            'type',
        ]));
        return ItemResource::make($item);
    }

    //create delete item method
    public function destroy(Item $item)
    {
        //delete the item
        $item->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }
}
