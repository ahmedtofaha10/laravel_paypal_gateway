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
Route::post('/paypal',[\App\Http\Controllers\PaypalController::class,'index'])->name('paypal_call');
Route::get('/paypal/return',[\App\Http\Controllers\PaypalController::class,'paypalReturn'])->name('paypal_return');
Route::get('/paypal/cancel',[\App\Http\Controllers\PaypalController::class,'paypalCancel'])->name('paypal_cancel');
