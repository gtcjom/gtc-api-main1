<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;

class PersonnelService
{

    public function index()
    {
        request()->validate([
            'column' => ['nullable', Rule::in(['name',])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])]
        ]);

        $keyword = request('keyword');
        return User::query()
            ->with(['personnel',])
            ->whereIn('type', userClinicTypes())
            ->when(request('type'), fn (Builder $builder) => $builder->where('type', request('type')))
            ->when(request('keyword'), fn (Builder $builder) => $builder->where('name', 'like', "%{$keyword}%"))
            ->when(
                request('column') && request('direction'),
                fn ($q) => $q->orderBy(request('column'), request('direction'))
            )
            ->when(
                request('column') && request('direction'),
                fn ($q) => $q->orderBy(request('column'), request('direction'))
            )
            ->latest()
            ->paginate(request('paginate', 12));
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = bcrypt(request()->get('password'));
        //$user->manage_by = "m-81632116452";
        $user->status = 1;
        $user->is_verify = 1;
        $user->is_confirm = 1;
        $user->main_mgmt_id = "general-magement-09202021";
        $this->setUserInformation($request, $user);
        $user->save();
        //TODO Email for credentials

        $personnel = new Personnel();
        $this->setInformation($request, $personnel, $user->id);
        $personnel->save();
        //TODO Additional information per type
        if ($request->get('type') == 'Clinic-Doctor' || $request->get('type') == 'HIS-Doctor') {
            $doctor = new Doctor();
            $this->setDocotorInformation($request, $doctor, $user);
        }

        if ($request->get('type') == 'Clinic-Nurse' || $request->get('type') == 'HIS-Nurse') {
            $nurse = new Nurse();
            $this->setNurseInformation($request, $nurse, $user);
        }
        $user->load(['personnel', 'barangayData', 'municipalityData', 'purokData',]);

        return $user;
    }

    public function update(Request $request, int $id)
    {
        $user = User::query()
            ->with('personnel')
            ->whereIn('type', userClinicTypes())
            ->findOrFail($id);
        $this->setUserInformation($request, $user);

        $user->save();

        if ($user->type == 'Clinic-Doctor' || $user->type == 'HIS-Doctor') {
            $doctor = Doctor::query()
                ->where('user_id', $user->user_id)
                ->first();
            if (!is_null($doctor))
                $this->setDocotorInformation($request, $doctor, $user);
        }

        if ($user->type == 'Clinic-Nurse' || $user->type == 'HIS-Nurse') {
            $nurse = Nurse::query()
                ->where('user_id', $user->user_id)
                ->first();
            if (!is_null($nurse))
                $this->setNurseInformation($request, $nurse, $user);
        }




        /*  $personnel = $user->personnel;

        $this->setInformation($request,$personnel,$user->id);

        $personnel->save();*/

        $user->load(['personnel',]);

        return $user;
    }

    public function destroy(int $id)
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);
            $peronnel = Personnel::where('user_id', $id)->first();
            if ($peronnel) {
                $peronnel->delete();
            }
            $user->delete();

            DB::commit();

            return response()->json(['message' => "Personnel deleted successfully"], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Action failed! try again later."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function setNurseInformation(Request $request, Model|Nurse $nurse, Model|User $user)
    {

        $nurse->user_fullname = $request->get('name');
        $nurse->user_id = $user->user_id;
        $nurse->management_id = $user->management_id;
        $nurse->added_by = $request->user()->user_id;
        $nurse->status = 1;
        $nurse->gender = $request->get('gender');
        $nurse->title = $request->get('title');
        $nurse->role = "User";
        $nurse->save();
    }



    public function setDocotorInformation(Request $request, Model|Doctor $doctor, Model|User $user)
    {

        $doctor->name = $request->get('name');
        $doctor->user_id = $user->user_id;
        $doctor->management_id = $user->management_id;
        $doctor->added_by = $request->user()->user_id;
        $doctor->online_appointment = 1;
        $doctor->status = 1;
        $doctor->specialization = $request->get('specialization');
        $doctor->gender = $request->get('gender');
        $doctor->title = $request->get('title');
        $doctor->role = "User";
        $doctor->save();
    }

    private function setUserInformation(Request $request, Model|User $user)
    {
        $user->name = $request->get('name');
        $user->street = $request->get('street');
        $user->region = $request->get('region');
        $user->province = $request->get('province');
        $user->municipality = $request->get('municipality');
        $user->barangay = $request->get('barangay');
        $user->purok = $request->get('purok');
        $user->type = $request->get('type');
        $user->title = $request->get('title');

        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('users/avatar');
        }
    }

    private function setInformation(Request $request, Model|Personnel $personnel, int $user_id)
    {
        $personnel->user_id = $user_id;
        $personnel->gender = $request->get('gender');
        $personnel->birthdate = $request->get('birthdate');
        $personnel->contact_number = $request->get('contact_number');
        $personnel->title = $request->get('title');
    }
}
