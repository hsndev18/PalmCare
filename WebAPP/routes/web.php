<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;




Route::get('/', function () {
    return view('welcome');
});

Route::post('/send-data', [HomeController::class, 'sendData'])->name('send-data');


