<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{

    public function dataList()
    {

        $keyword = request('keyword');
        return User::query()
            // ->whereIn('type', userTypes())


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
            ->latest()->get();
    }

    public function create()
    {
        $user = new User();
        $user->username = request()->get('username');
        $user->name = request()->get('name');
        $user->password = bcrypt(request()->get('password'));
        $user->type = request()->get('type');
        $user->email = request()->get('email');
        $user->manage_by = "m-81632116452";
        $user->status = 1;
        $user->is_verify = 1;
        $user->is_confirm = 1;
        $user->main_mgmt_id = "general-magement-09202021";
        $user->region = request()->get('region');
        $user->province = request()->get('province');
        $user->municipality = request()->get('municipality');
        $user->barangay = request()->get('barangay');
        $user->purok = request()->get('purok');
        $user->street = request()->get('street');
        $user->save();

        return $user;
    }

    public function update(int $id)
    {
        $user = User::query()->findOrFail($id);
        $user->username = request()->get('username');
        $user->name = request()->get('name');
        $user->password = bcrypt(request()->get('password'));
        $user->type = request()->get('type');
        $user->email = request()->get('email');
        $user->region = request()->get('region');
        $user->province = request()->get('province');
        $user->municipality = request()->get('municipality');
        $user->barangay = request()->get('barangay');
        $user->purok = request()->get('purok');
        $user->street = request()->get('street');
        $user->save();

        return $user;
    }

    public function profileUpdate()
    {
        $user = request()->user();

        $user->name = request()->get('name');
        $user->email = request()->get('email');

        if (request()->hasFile('avatar')) {
            $user->avatar = request()->file('avatar')->store('users/avatar');
        }
        $user->save();

        return $user;
    }

    public function changeUsername()
    {
        $user = request()->user();

        if (!Hash::check(request()->get('password'), $user->password))
            ValidationException::withMessages(['password' => 'Invalid Password']);

        $user->username = request()->get('username');

        return $user;
    }

    public function show()
    {
        return request()->user();
    }
}
