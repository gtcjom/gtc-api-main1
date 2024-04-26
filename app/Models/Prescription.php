<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $dates = ['datetime'];


    public function addedBy()
    {
        return $this->belongsTo(User::class,'added_by_id');
    }
}
