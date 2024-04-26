<?php

namespace App\Http\Controllers\TeleMedicine;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeleMedicineScheduleResource;
use App\Http\Resources\UserResource;
use App\Models\TeleMedicineSchedule;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ScheduleController extends Controller
{

    public function index()
    {

        $date = date('Y-m-d', strtotime(request('date')));
        $today = date('Y-m-d');


        if ($date < $today) {
            return response()->json(['message' => 'Date is not valid'], 422);
        }


        $isDateToday = false;
        if ($date == $today) {
            $isDateToday = true;
        }


        $timesSlots = TimeSlot::query()->orderBy('rank', 'asc')->get();
        $schedules = TeleMedicineSchedule::query()->where('doctor_id', request('doctor_id'))->where('date', request('date'))->get();

        return $timesSlots->filter(function ($slot) use ($schedules, $isDateToday) {

            //Validate the current time plus 30mins and slot time
            $currentTime = date('H:i:s');
            $slotTime = date('H:i:s', strtotime($slot->start_time));


            $timed = true;
            if ($isDateToday && ($currentTime > $slotTime)) {
                $timed = false;
            }

            return !$schedules->contains('slot_id', $slot->id) && $timed;
        });
    }


    public function getAvailableSlotsPerMonth()
    {
        //Get the current month
        $month = date('m');
        $year = date('Y');
        //Get the days of the month  and store in arrays

        $days = [];
        $currentDay = 1;
        $monthSelected = request('month', date('m'));
        $yearSelected = request('year', date('Y'));



        if ($yearSelected < $year) {
            return [];
        }

        if ($yearSelected == $year && $monthSelected < $month) {
            return [];
        }

        $currentMonthDays = cal_days_in_month(CAL_GREGORIAN, $monthSelected, $yearSelected);


        //Check if the current month is the same with the month in the request and year
        if ($monthSelected == $month && $yearSelected == $year) {
            $currentDay = date('d');
        }

        for ($i = $currentDay; $i <= $currentMonthDays; $i++) {
            $day = $i;
            if ($i < 10) {
                $day = '0' . $i;
            }
            $date = $yearSelected . '-' . $monthSelected . '-' . $day;
            $days[] = $date;
        }

        //Get schedules of the current month exclude past dates and group by day

        $schedules = TeleMedicineSchedule::query()
            ->where('doctor_id', request('doctor_id'))
            ->whereMonth('date', '<=', $monthSelected)
            ->whereYear('date', '<=', $yearSelected)
            ->get();







        // loop all the days and get count slots available
        $availableSlots = [];
        $timesSlots = TimeSlot::query()->orderBy('rank', 'asc')->get();
        foreach ($days as $day) {
            $availableSlots[] = [
                'date' => $day,
                'slots' => $this->getAvailableSlotsPerDay($day, $schedules->where('date', $day), $timesSlots)
            ];
        }


        return $availableSlots;
    }


    public function store()
    {


        $slot = TimeSlot::query()->find(request('slot_id'));

        //validate the current time + 30mins and slot time




        $currentTime = date('H:i:s');
        $slotTime = date('H:i:s', strtotime($slot->start_time));

        //Validate the date if it is not a past date
        $date = date('Y-m-d', strtotime(request('date')));
        $today = date('Y-m-d');
        if ($date < $today) {
            return response()->json(['message' => 'Date is not valid'], 422);
        }

        //Check date if the date is today

        $isDateToday = false;
        if ($date == $today) {
            $isDateToday = true;
        }

        $timed = true;
        if ($isDateToday && ($currentTime > $slotTime)) {
            $timed = false;
        }
        $isAvailable = TeleMedicineSchedule::query()
            ->where('doctor_id', request('doctor_id'))
            ->where('date', request('date'))
            ->where('slot_id', request('slot_id'))
            ->doesntExist();

        if (!$isAvailable || !$timed) {
            return response()->json(['message' => 'Slot is not available'], 422);
        }

        $book = new TeleMedicineSchedule();
        $book->doctor_id = request('doctor_id');
        $book->patient_id = request('patient_id');
        $book->date = request('date');
        $book->slot_id = request('slot_id');
        $book->status = 'pending';
        $book->uuid = rand(10000000, 99999999);;
        $book->channel_name = Str::uuid();
        $book->notes = request('notes');
        $book->token = "";
        $book->save();

        return $book;
    }
    // public function updateSchedule($id) {
    //     $appointment = TeleMedicineSchedule::query()->firstOrFail($id);
    //     $appointment->s
    // }
    public function AllSchedules()
    {
        $appointments = TeleMedicineSchedule::query()->get();
        return TeleMedicineScheduleResource::collection($appointments);
    }
    public function patientSchedules()
    {
        $appointments = TeleMedicineSchedule::query()->where('patient_id', request('patient_id'))->get();
        return TeleMedicineScheduleResource::collection($appointments);
    }
    public function doctorSchedules()
    {
        $appointments = TeleMedicineSchedule::query()->where('doctor_id', request('doctor_id'))->get();
        return TeleMedicineScheduleResource::collection($appointments);
    }
    public function showDoctorSchedules($id)
    {
        $appointment = TeleMedicineSchedule::query()->where('doctor_id', request('doctor_id'))->find($id);

        $doctor = User::find($appointment->doctor_id);
        $patient = User::find($appointment->patient_id);


        return response()->json([
            'appointment' => $appointment,
            'doctor' => UserResource::make($doctor),
            'patient' => UserResource::make($patient),
        ], Response::HTTP_OK);
    }
    private function getAvailableSlotsPerDay(mixed $day, mixed $schedules, mixed $timesSlots)
    {
        //If the day is less than today return zero
        $date = date('Y-m-d', strtotime($day));
        $today = date('Y-m-d');
        if ($date < $today) {
            return 0;
        }
        //Count available time slots


        $isDateToday = false;
        if ($date == $today) {
            $isDateToday = true;
        }

        $filterSlots =  $timesSlots->filter(function ($slot) use ($schedules, $isDateToday) {

            //Validate the current time plus 30mins and slot time
            $currentTime = date('H:i:s');
            $slotTime = date('H:i:s', strtotime($slot->start_time));


            $timed = true;
            if ($isDateToday && ($currentTime > $slotTime)) {
                $timed = false;
            }

            return !$schedules->contains('slot_id', $slot->id) && $timed;
        });


        return $filterSlots->count();
    }
}
