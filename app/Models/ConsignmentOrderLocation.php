<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignmentOrderLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'location'
    ];


    public function items()
    {
        return $this->hasMany(ConsignmentOrderDetail::class, 'consignment_order_location_id');
    }
    public function healthUnit()
    {
        return $this->belongsTo(HealthUnit::class, 'location_id');
    }
}
