<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::post('request_otp',[App\Http\Controllers\RequestOtpController::class,'requestOtp']);
Route::get('send_otp/{email}',[App\Http\Controllers\RequestOtpController::class,'otp']);
Route::post('send_otp/{email}',[App\Http\Controllers\RequestOtpController::class,'sendOtp']);
