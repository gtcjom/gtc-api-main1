<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClinicResources;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ClinicService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClinicPersonnelController extends Controller
{

    public function index()
    {
        request()->validate([
            'column' => ['nullable', Rule::in(['name',])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])]
        ]);
        $users = User::notYetAssignWithClinic(request('clinic_id'))
            ->when(request('type'), fn (Builder $builder) => $builder->where('type', request('type')))
            ->when(request('keyword'), fn (Builder $builder)  => $builder->where('name', 'like', '%' . request('keyword') . '%'))
            ->when(
                request('column') && request('direction'),
                fn ($q) => $q->orderBy(request('column'), request('direction'))
            )
            ->paginate(request('paginate') ?? 12);

        return UserResource::collection($users);
    }

    public function show(ClinicService $clinicService, int $id)
    {
        return UserResource::collection($clinicService->getPersonnels($id));
    }

    public function update(ClinicService $clinicService, int $id)
    {

        return ClinicResources::make($clinicService->updatePersonnel($id));
    }
}
