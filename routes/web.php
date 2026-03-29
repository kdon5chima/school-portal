<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Using string syntax to bypass Class resolution errors
Route::get('/student/{student}/result', 'App\Http\Controllers\ResultController@download')->name('student.result');