<?php

use App\Models\Clinic;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('clinic.{clinicId}', function ($user, $clinicId) {
    return Clinic::query()
        ->whereHas( 'personnelList' , function ($query) use ($user){
            $query->where('user_id', $user->id);
        })->where('id',$clinicId)->exists();
});

Broadcast::channel('doctor-indicator', function ($user) {
    if(!is_null($user)){
        $id = $user->id.'_'.config('app.entity').'_'.config('app.entity_key').'_'.config('app.entity_unit');
        //remove whitespace
        $id = preg_replace('/\s+/', '', $id);
        $id = trim($id);
        return [
            'id' => $id,
            'user_id' => $user->id,
            'name' => $user->name,
            'type' => $user->type,
            'entity' => config('app.entity'),
            'municipality' => config('app.entity_key'),
            'barangay' => config('app.entity_unit'),
            'health_unit_id' => $user->health_unit_id,
        ];
    }


});
