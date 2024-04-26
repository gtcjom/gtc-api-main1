<?php

use App\Http\Controllers\Patient\PatientsController;
use Illuminate\Support\Facades\Route;


Route::prefix('pho')->group(function (){
    Route::post('patients', [PatientsController::class,'store']);
    Route::post('patients/{id}', [PatientsController::class,'update']);
});