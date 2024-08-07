<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\ProfileController;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        "appVersionNumber" => config("app.version"),
    ]);
});

Route::middleware('auth')->get('/contactme', function() {
    return Inertia::render('ContactMe', [
        'avatarImage' => auth()->user()->avatar_image,
    ]);
})->name('contactme');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('problems', ProblemController::class);
    Route::get('problems/{problem}/run', [ProblemController::class, 'run'])->name('problems.run');
    Route::get('problems/{problem}/submit', [ProblemController::class, 'submit'])->name('problems.submit');
});
