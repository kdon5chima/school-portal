<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResultCheckerController;

// Page where parents type the Admission Number
Route::get('/check-results', [ResultCheckerController::class, 'index'])->name('results.index');

// Page that displays the specific student's grades
Route::post('/view-result', [ResultCheckerController::class, 'show'])->name('results.show');
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

// The landing page for Unique Group of Schools
Route::get('/', [HomeController::class, 'index'])->name('home');

// Filament routes are automatically handled by the package, 
// but your custom home page now links to /admin.