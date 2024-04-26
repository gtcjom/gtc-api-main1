<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInventory extends Model
{
    use HasFactory;

    protected $table = 'item_inventories';

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
