<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatingRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'capacity',
    ];

    public function healthUnit()
    {
        return $this->belongsTo(HealthUnit::class, 'health_unit_id');
    }
}
