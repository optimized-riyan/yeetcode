<?php

use App\Http\Controllers\ProblemController;
use App\Models\Problem;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('problemset');
});


Route::resource('problems', ProblemController::class);
Route::controller(ProblemController::class)->group(function () {
    Route::get('problems/{problem}/run', 'run')->name('problems.run');
    Route::get('problems/{problem}/submit', 'submit')->name('problems.submit');
    Route::get('problems/get-problems', 'get_problems')->name('problems.get-problems');
});
