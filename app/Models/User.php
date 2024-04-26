<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'username',
        'type',
        'region',
        'province',
        'municipality',
        'email',
        'title',
        'manage_by',
        'is_confirm',
        'is_verify',
        'main_mgmt_id',
        'password',
        'name',
        'barangay',
        'purok',
        'status',
        'room_id',
        'specialty_id',
        'gender',
        'rhu_id',
        'bhs_id',
        'ph_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->user_id = 'u-' . rand(0, 8888) . time();
        });
    }
    public function municipalityData()
    {
        return $this->belongsTo(Municipality::class, 'municipality');
    }
    public function specialty()
    {
        return $this->belongsTo(Specialties::class, 'specialty_id');
    }
    public function healthUnit()
    {
        return $this->belongsTo(HealthUnit::class, 'health_unit_id');
    }

    public function room()
    {
        return $this->belongsTo(OperatingRoom::class, 'room_id');
    }


    public function barangayData()
    {
        return $this->belongsTo(Barangay::class, 'barangay');
    }
    public function purokData()
    {
        return $this->belongsTo(Purok::class, 'purok');
    }

    public function personnel()
    {
        return $this->hasOne(Personnel::class, 'user_id');
    }

    public function assignClinic()
    {
        return $this->hasOne(ClinicPersonnel::class, 'user_id');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', 'user_id');
    }


    public function scopeNotYetAssignWithClinic($query, int $id)
    {
        $query
            ->whereIn('type', userClinicTypes())
            ->where(function ($query) use ($id) {
                $query->doesntHave('assignClinic')
                    ->orWhereHas('assignClinic', function ($query) use ($id) {
                        $query->where('clinic_id', $id);
                    });
            });
    }
}
