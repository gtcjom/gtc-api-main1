<?php

namespace App\Http\Controllers\V2\Patient;

use App\Http\Controllers\Controller;
use App\Models\Vital;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VitalChartController extends Controller
{
	public function getTemperatures($id)
	{
		$temperatures = [];
		$patientVitals = Vital::where('patient_id', $id)->orderBy('created_at', 'desc')->get();

		foreach($patientVitals as $patientVital){
			// insert to array of temperatures
			array_push($temperatures, [
				"temperature" => $patientVital->temperature,
				"date" => Carbon::parse($patientVital->created_at)->format('M d, Y h:i:s a'),
			]);
		}
		return response()->json([
			'temperatures' => $temperatures,
			'message' => 'Patient temperatures retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function getBloodPressures($id)
	{
		$blood_pressures = [];
		$patientVitals = Vital::where('patient_id', $id)->orderBy('created_at', 'desc')->get();

		foreach($patientVitals as $patientVital){
			// insert to array of blood pressures
			array_push($blood_pressures,[
				"systolic" => $patientVital->blood_systolic,
				"diastolic" => $patientVital->blood_diastolic,
				"date" => Carbon::parse($patientVital->created_at)->format('M d, Y h:i:s a')
			]);
		}
		return response()->json([
			'blood_pressures' => $blood_pressures,
			'message' => 'Patient blood pressures retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function getGlucoses($id)
	{
		$glucoses = [];
		$patientVitals = Vital::where('patient_id', $id)->orderBy('created_at', 'desc')->get();

		foreach($patientVitals as $patientVital){
			// insert to array of glucoses
			array_push($glucoses,[
				"glucose" => $patientVital->glucose,
				"date" => Carbon::parse($patientVital->created_at)->format('M d, Y h:i:s a')
			]);
		}
		return response()->json([
			'glucoses' => $glucoses,
			'message' => 'Patient glucoses retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function getCholesterols($id)
	{
		$cholesterols = [];
		$patientVitals = Vital::where('patient_id', $id)->orderBy('created_at', 'desc')->get();

		foreach($patientVitals as $patientVital){
			// insert to array of cholesterols
			array_push($cholesterols,[
				"cholesterol" => $patientVital->cholesterol,
				"date" => Carbon::parse($patientVital->created_at)->format('M d, Y h:i:s a')
			]);
		}
		return response()->json([
			'cholesterols' => $cholesterols,
			'message' => 'Patient cholesterols retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function getPulses($id)
	{
		$pulses = [];
		$patientVitals = Vital::where('patient_id', $id)->orderBy('created_at', 'desc')->get();

		foreach($patientVitals as $patientVital){
			// insert to array of pulses
			array_push($pulses,[
				"pulse" => $patientVital->pulse,
				"date" => Carbon::parse($patientVital->created_at)->format('M d, Y h:i:s a')
			]);
		}
		return response()->json([
			'pulses' => $pulses,
			'message' => 'Patient pulses retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function getRespiratoryRates($id)
	{
		$respiratory_rates = [];
		$patientVitals = Vital::where('patient_id', $id)->orderBy('created_at', 'desc')->get();

		foreach($patientVitals as $patientVital){
			// insert to array of respiratory_rates
			array_push($respiratory_rates,[
				"respiratory" => $patientVital->respiratory,
				"date" => Carbon::parse($patientVital->created_at)->format('M d, Y h:i:s a')
			]);
		}
		return response()->json([
			'respiratory_rates' => $respiratory_rates,
			'message' => 'Patient respiratory rates retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function getUricAcids($id)
	{
		$uric_acids = [];
		$patientVitals = Vital::where('patient_id', $id)->orderBy('created_at', 'desc')->get();

		foreach($patientVitals as $patientVital){
			// insert to array of uric_acids
			array_push($uric_acids,[
				"uric_acid" => $patientVital->uric_acid,
				"date" => Carbon::parse($patientVital->created_at)->format('M d, Y h:i:s a')
			]);
		}
		return response()->json([
			'uric_acids' => $uric_acids,
			'message' => 'Patient uric acids retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function getHeights($id)
	{
		$heights = [];
		$patientVitals = Vital::where('patient_id', $id)->orderBy('created_at', 'desc')->get();

		foreach($patientVitals as $patientVital){
			// insert to array of heights
			array_push($heights,[
				"height" => $patientVital->height,
				"date" => Carbon::parse($patientVital->created_at)->format('M d, Y h:i:s a')
			]);
		}
		return response()->json([
			'heights' => $heights,
			'message' => 'Patient heights retrieved successfully.'
		], Response::HTTP_OK);
	}

	public function getWeights($id)
	{
		$weights = [];
		$patientVitals = Vital::where('patient_id', $id)->orderBy('created_at', 'desc')->get();

		foreach($patientVitals as $patientVital){
			// insert to array of weights
			array_push($weights,[
				"weight" => $patientVital->weight,
				"date" => Carbon::parse($patientVital->created_at)->format('M d, Y h:i:s a')
			]);
		}
		return response()->json([
			'weights' => $weights,
			'message' => 'Patient weights retrieved successfully.'
		], Response::HTTP_OK);
	}
}
