<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('/dashboard')->group(function () {
    Route::post('/store', [StudentController::class, "store"])->name('dashboard.store');
    Route::get('/list', [StudentController::class, "list"])->name('dashboard.list');
    Route::delete('/{student_id}/delete', [StudentController::class, "delete"])->name('dashboard.delete');
    Route::get('/{student_id}/statusChange', [StudentController::class, "statusChange"])->name('dashboard.statusChange');
    Route::get('/{student_id}/get', [StudentController::class, "get"])->name('dashboard.get');
    Route::post('/{student_id}/update', [StudentController::class, "update"])->name('dashboard.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
