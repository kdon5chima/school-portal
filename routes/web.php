<?php

namespace App\Http\Controllers; // Ensure this isn't here in web.php, only in the Controller

use App\Http\Controllers\HomeController; // THIS MUST BE HERE
use App\Http\Controllers\ResultCheckerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportCardController;

// Using a distinct route name to avoid conflict
Route::get('/generate-report/{id}', [ReportCardController::class, 'generate'])->name('report.generate');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Result Checker Routes for Parents
Route::get('/check-results', [ResultCheckerController::class, 'index'])->name('results.index');
Route::post('/view-result', [ResultCheckerController::class, 'show'])->name('results.show');

// Result Checker Routes for Parents (Combined & Unique)
Route::get('/check-results', [ResultCheckerController::class, 'index'])->name('results.index');
Route::post('/view-result', [ResultCheckerController::class, 'show'])->name('results.show');

// 2. PROTECTED ROUTES (Requires Login)
Route::middleware(['auth'])->group(function () {
    // Custom protected routes would go here
});

/*
| Note: Filament Admin routes (/admin) are handled automatically 
| by the Filament package.
*/