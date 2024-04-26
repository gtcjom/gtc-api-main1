<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $dates = ['date'];


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function environmentalHistory()
    {
        return $this->hasOne(EnvironmentalHistory::class);
    }

    public function socialHistory()
    {
        return $this->hasOne(SocialHistory::class);
    }
}
