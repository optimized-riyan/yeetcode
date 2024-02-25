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


Route::get('/problemset', function () {
    return Inertia::render('ProblemSet', [
        'problemList' => Problem::with('difficulty')->get(),
    ]);
})->name('problemset');


Route::resource('problems', ProblemController::class)->only([
    'show', 'store'
]);
Route::get('problems/{problem}/run', [ProblemController::class, 'run'])->name('problems.run');
Route::get('problems/{problem}/submit', [ProblemController::class, 'submit'])->name('problems.submit');
