<?php

namespace App\Http\Controllers;

use App\Models\AppointmentData;
use App\Models\Barangay;
use App\Models\Purok;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CensusController extends Controller
{

    public function censusSummary()
	{
        $totalPopulation = 300000;
		$totalSurvey = DB::table('patients')->count();

		$totalMale = DB::table('patients')
			->where('gender', 'male')
			->count();

		$totalFemale = DB::table('patients')
			->where('gender', 'female')
			->count();

		$totalUnknownGender = DB::table('patients')
			->where('gender', '')
			->orWhere('gender', null)
			->count();

		$totalChildren = DB::table('patients')
			->where('birthday', '<=', Carbon::now()->toDateString())
			->where('birthday', '>', Carbon::now()->subYears(18)->toDateString())
			->orderBy('birthday', 'desc')
			->count();

		$totalAdults = DB::table('patients')
			->where('birthday', '<=', Carbon::now()->subYears(18)->toDateString())
			->where('birthday', '>', Carbon::now()->subYears(60)->toDateString())
			->count();

		$totalSeniors = DB::table('patients')
			->where('birthday', '<=', Carbon::now()->subYears(60)->toDateString())
			->count();

		$invalidBirthdates = DB::table('patients')
			->where('birthday', '>', Carbon::now()->toDateString())
			->count();

        $appointment_data = AppointmentData::query()->count();
        $tuberculosis = AppointmentData::query()->where('post_notes', 'tuberculosis')->count();
        $today_appointment = AppointmentData::query()->whereDate('created_at', Carbon::now()->toDateString())->count();
        $done_appointment = AppointmentData::query()
            ->where('post_notes', 'tuberculosis')
            ->where('status','done')->count();
        $done_today_appointment = AppointmentData::query()
            ->where('post_notes', 'tuberculosis')
            ->where('status','done')->whereDate('updated_at', Carbon::now()->toDateString())->count();

		$data = [
			'total_population' => $totalPopulation,
            'total_survey' => $totalSurvey,
            'survey_percentage' => number_format(($totalSurvey / $totalPopulation) * 100, 2),
			'total_male' => $totalMale,
			'total_female' => $totalFemale,
			'total_unknown_gender' => $totalUnknownGender,
			'total_children' => $totalChildren,
			'total_adults' => $totalAdults,
			'total_seniors' => $totalSeniors,
			'total_invalid_birthdates' => $invalidBirthdates,
            'appointment_data' => $appointment_data,
            'tuberculosis' => $tuberculosis,
            'tuberculosis_percentage' => number_format(($tuberculosis / $appointment_data) * 100, 2),
            'today_appointment' => $today_appointment,
            'done_appointment' => $done_appointment,
            'done_today_appointment' => $done_today_appointment,
            'done_percentage' => number_format(($done_appointment / $tuberculosis) * 100, 2),
		];

		return response()->json([
			'data' => $data,
			'message' => "Census summary retrieved successfully"
		]);
	}

	public function municipalityPopulation(Request $request)
	{
		$data = [];
		$municipality_id = $request->municipality_id;

		$municipalities = DB::table('municipalities')
			->when($municipality_id, function($query, $municipality_id){
				$query->where('id', $municipality_id);
			})->get();

		foreach ($municipalities as $municipality){
			$count = DB::table('patients')->where('municipality_id', $municipality->id)->count();
			array_push($data, [
				'id' => $municipality->id,
				'municipality' => $municipality->name,
				'population' => $count
			]);
		}

		return response()->json([
			'data' => $data,
			'message' => "Municipality population retrieved successfully"
		]);
	}

	public function barangayPopulation(Request $request)
	{
		$data = [];
		$barangay_id = $request->barangay_id;

		$barangays = Barangay::with(['municipality'])
			->when($barangay_id, function($query, $barangay_id){
				$query->where('id', $barangay_id);
			})->get();

		foreach ($barangays as $barangay){
			$count = DB::table('patients')->where('barangay_id', $barangay->id)->count();
			array_push($data, [
				'id' => $barangay->id,
				'barangay' => $barangay->name,
				'municipality' => $barangay->municipality->name,
				'population' => $count,
			]);
		}

		return response()->json([
			'data' => $data,
			'message' => "Barangay population retrieved successfully"
		]);
	}

	public function purokPopulation(Request $request)
	{
		$data = [];
		$purok_id = $request->purok_id;

		$puroks = DB::table('puroks')
			->when($purok_id, function($query, $purok_id){
				$query->where('id', $purok_id);
			})->get();

		foreach ($puroks as $purok){
			$count = DB::table('patients')->where('purok_id', $purok->id)->count();
			array_push($data, [
				'id' => $purok->id,
				'purok' => $purok->name,
				'population' => $count,
			]);
		}

		return response()->json([
			'data' => $data,
			'message' => "Purok population retrieved successfully"
		]);
	}
}
