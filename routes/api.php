<?php

use App\Http\Controllers\ProblemController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-problems', [ProblemController::class, 'getProblemsByTitle']);

Route::get('/get-topics', [ProblemController::class, 'getTopicsWithFilter']);

Route::post('/runTrivial', [ProblemController::class, 'runTrivial']);

Route::post('/submitCode', [SubmissionController::class, 'submitCode']);

Route::get("/getSubmissions/{problem}/{user}", [SubmissionController::class, "getSubmissions"]);

Route::get("/getErrorTc/{submission}", [SubmissionController::class, "getErrorneousTc"]);
