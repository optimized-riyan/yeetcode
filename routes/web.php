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


Route::resource('problems', ProblemController::class)->only([
    'show', 'store', 'create', 'index',
]);
Route::get('problems/{problem}/run', [ProblemController::class, 'run'])->name('problems.run');
Route::get('problems/{problem}/submit', [ProblemController::class, 'submit'])->name('problems.submit');
Route::get('problems/create', [ProblemController::class, 'create'])->name('problems.create');
