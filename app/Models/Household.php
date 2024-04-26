<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;


    public function sanitation()
    {
        return $this->hasOne(Sanitation::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class ,'city');
    }

    public function barangayData()
    {
        return $this->belongsTo(Barangay::class,'barangay');
    }
    public function purokData()
    {
        return $this->belongsTo(Purok::class, 'purok');
    }


    public function housing(){
        return $this->hasOne(Housing::class);
    }

    public function waste()
    {
        return $this->hasOne(WasteManagement::class);
    }

    public function calamity(){
        return $this->hasOne(Calamity::class);
    }

    public function income()
    {
        return $this->hasMany(SourceIncome::class);
    }

    public function rawAnswer()
    {
        return $this->hasOne(HouseRawAnswer::class);
    }

    public function members()
    {
        return $this->hasMany(Patient::class,'household_id','id');
    }

    public function houseCharacteristics()
    {
        return $this->hasOne(HouseCharacteristic::class);
    }

    public function houseHoldCharacteristics()
    {
        return $this->hasOne(HouseholdCharacteristic::class);
    }
}
