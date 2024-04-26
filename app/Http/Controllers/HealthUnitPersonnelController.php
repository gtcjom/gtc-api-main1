<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HealthUnitPersonnelController extends Controller
{

    public function index()
    {
        $keyword = request('keyword');
        $users =  User::query()
            ->when(
                request('type'),
                function (Builder $q) {
                    if (is_array(explode(',', request('type')))) {
                        return $q->whereIn('type', explode(',', request('type')));
                    } else {
                        return $q->where('type', request('type'));
                    }
                }
            )
            ->when(request('keyword'), fn (Builder $builder) => $builder->where('name', 'like', "%{$keyword}%"))
            ->when(
                request('column') && request('direction'),
                fn ($q) => $q->orderBy(request('column'), request('direction'))
            )
            ->paginate(request('paginate', 12));
        return UserResource::collection($users);
    }

    public function store()
    {
        $user = new User();
        $user->username = request('username');
        $user->email = request('email');
        $user->password = request('password');
        $user->street = request('street');
        $user->region = request('region');
        $user->province = request('province');
        $user->municipality = request('municipality');
        $user->barangay = request('barangay');
        $user->purok = request('purok');
        $user->type = request('type');
        $user->name = request('name');
        $user->title = request('title');
        $user->gender = request('gender');
        $user->specialty_id = request('specialty_id');
        $user->health_unit_id = request('health_unit_id');

        //$user->contact_number = request('contact_number');
        $user->status = request('status');

        if (request()->hasFile('avatar')) {
            $user->avatar = request()->file('avatar')->store('users/avatar');
        }

        $user->save();

        return UserResource::make($user);
    }

    public function update($id)
    {
        $user = User::query()->findOrFail($id);
        $user->username = request('username');
        $user->email = request('email');
        $user->password = request('password');
        $user->street = request('street');
        $user->region = request('region');
        $user->province = request('province');
        $user->municipality = request('municipality');
        $user->barangay = request('barangay');
        $user->purok = request('purok');
        $user->type = request('type');
        $user->name = request('name');
        $user->title = request('title');
        $user->gender = request('gender');
        $user->specialty_id = request('specialty_id');
        $user->health_unit_id = request('health_unit_id');

        //$user->contact_number = request('contact_number');
        $user->status = request('status');

        if (request()->hasFile('avatar')) {
            $user->avatar = request()->file('avatar')->store('users/avatar');
        }


        $user->save();

        return UserResource::make($user);
    }
    public function activate($id)
    {
        $user = User::query()->findOrFail($id);
        $user->status = 'active';
        $user->save();

        return UserResource::make($user);
    }
    public function deactivate($id)
    {
        $user = User::query()->findOrFail($id);
        $user->status = 'inactive';
        $user->save();

        return UserResource::make($user);
    }
    public function assignment($id)
    {
        $user = User::query()->findOrFail($id);
        $user->health_unit_id = request('health_unit_id');
        $user->room_id = request('room_id');
        $user->save();

        return UserResource::make($user);
    }
}
