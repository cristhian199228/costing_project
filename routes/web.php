<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\TracingController;
use App\Http\Controllers\api\v1\AttentionController;
use App\Http\Controllers\api\v1\EarlyTemperatureController;
use App\Http\Controllers\api\v1\LateTemperatureController;
use App\Http\Controllers\api\v1\PatientController;
use App\Http\Controllers\api\v1\SymptomsController;
use App\Http\Controllers\api\v1\BinnacleController;
use App\Http\Controllers\api\v1\EpidemiologicalHistoriesController;
use App\Http\Controllers\api\v1\DirectContactsController;

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

Route::view('/', 'welcome');

Route::prefix('/api/v1')->group(function () {
    Route::apiResources([
        'tracing' => TracingController::class,
        'attention' => AttentionController::class,
        'et' => EarlyTemperatureController::class,
        'lt' => LateTemperatureController::class,
        'symptom' => SymptomsController::class,
        'binnacle' => BinnacleController::class,
        'eh' => EpidemiologicalHistoriesController::class,
        'dc' => DirectContactsController::class,
    ]);

    Route::post('/login', [PatientController::class, 'login']);
    Route::get('/search/patient', [PatientController::class, 'index']);
    Route::get('/tracingPlanilla', [TracingController::class, 'tracingPlanilla']);
});


