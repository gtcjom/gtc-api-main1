<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clinic extends Model
{
    use HasFactory;

    protected $table = 'clinic';


    // public function municipality(): BelongsTo
    // {
    //     return $this->belongsTo(Municipality::class);
    // }

    // public function barangay(): BelongsTo
    // {
    //     return $this->belongsTo(Barangay::class);
    // }
    // public function purok(): BelongsTo
    // {
    //     return $this->belongsTo(Purok::class);
    // }

    public function personnelList()
    {
        return $this->hasMany(ClinicPersonnel::class, 'clinic_id');
    }

    public function personnels()
    {
        return $this->belongsToMany(User::class, 'clinic_personnels', 'clinic_id', 'user_id');
    }

    public function doctors()
    {
        return $this->belongsToMany(User::class, 'clinic_personnels', 'clinic_id', 'user_id')->where('users.type', 'CIS-Doctor');
    }
}
