<?php

use App\Models\AppointmentData;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Storage;




Route::get('test-mhra', function (){
//   $patient = new \App\Services\MHRA\PatientServices();
//    return $patient->createPatient(99888);

//
//    $levelService = new \App\Services\MHRA\LevelService();
//    return $levelService->makeLevelOne(99888);
//
//    $example = new \App\Services\MHRA\ExampleService();

    $fireService = new \App\Services\MHRA\FireServerService();

    return $fireService->getList();

    return $example->bundle();
});
Route::get('ping',[\App\Http\Controllers\Philhealth\PhilhealthSoapController::class,'ping']);
Route::get('pin',[\App\Http\Controllers\Philhealth\PhilhealthSoapController::class,'isClaimEligible']);
Route::get('/', function () {
    Storage::disk('do')->put('example2.txt', 'Contents');
    return view('welcome');
});

Route::get('cloud-to-local-patient', [\App\Http\Controllers\PHO\Cloud\UnSyncedPatientsController::class, 'update']);
Route::get('test',function (){



    /*$client = new \GuzzleHttp\Client();

    $url = config('app.cloud_url') . '/test';
    $response = $client->request(
        'GET',
        $url
    );

    $body = json_decode($response->getBody())->data->cloud_id;

    return $body;*/

});

Route::get('agora-generate-token',[\App\Http\Controllers\AgoraController::class,'GetToken']);


