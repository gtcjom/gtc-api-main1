<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'name',
        'description',
        'unit_measurement',
        'type',
    ];
    // public function inventory()
    // {
    //     // $inventory = ItemInventory::query()
    //     //     ->where('location_id', request()->user()->health_unit_id)
    //     //     ->where('item_id', $this->id)
    //     //     ->first();
    //     // return $inventory;
    //     // return  $this->hasOne(ItemInventory::class, 'item_id', 'id')->where('location_id', request()->user()->health_unit_id);
    // }

    public function inventory()
    {
        return $this->hasOne(ItemInventory::class, 'item_id', 'id');
    }
    public function qtyLeft()
    {
        $cnor_location = HealthUnit::query()->where('type', 'CNOR')->first();
        $inventory = ItemInventory::query()
            ->where('location_id', $cnor_location->id)
            ->where('item_id', $this->id)->first();
        return $inventory?->quantity ?: 0;
    }
}
