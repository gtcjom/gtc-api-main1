<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['management_id'];


    protected static function booted()
    {
        static::creating(function ($user) {
            $user->d_id = 'd-'.time();
            $user->doctors_id = 'doctor-'.time();

        });
    }

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'user_id');
	}
}
