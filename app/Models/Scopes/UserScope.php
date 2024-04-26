<?php

namespace App\Models\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
class UserScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $user = request()->user();

        if(isset($user))
        $builder->when(
            $user->type == "MUNICIPAL-HO",
            fn(Builder $q) => $q->where('municipality_id',$user->municipality)
        )
            ->when(
                $user->type == "BARANGAY-HO",
                fn(Builder $q) => $q->where([
                    'municipality_id' => $user->municipality,
                    'barangay_id' => $user->barangay
                ])
            )
            ->when(
                $user->type == "PUROK-HO",
                fn(Builder $q) => $q->where([
                    'municipality_id' => $user->municipality,
                    'barangay_id' => $user->barangay,
                    'purok_id' => $user->purok
                ])
            );
    }
}