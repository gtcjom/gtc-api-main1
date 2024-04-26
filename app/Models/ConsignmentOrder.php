<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignmentOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'cof_number',
        'consignor',
        'term',
        'status',
        'hci_name',
        'hci_number',
        'to_location_type',
        'to_location_id',
        'from_location_type',
        'from_location_id',
    ];

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
    public function scheduledBy()
    {
        return $this->belongsTo(User::class, 'scheduled_by', 'id');
    }
    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by', 'id');
    }
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by', 'id');
    }
    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by', 'id');
    }
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by', 'id');
    }
    public function locations()
    {
        return $this->hasMany(ConsignmentOrderLocation::class, 'consignment_order_id', 'id');
    }
    public function toLocation()
    {
        return $this->belongsTo(ConsignmentOrderLocation::class, 'to_location_id', 'id');
    }
}
