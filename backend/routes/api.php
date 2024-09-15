<?php

use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('guest')->group(function () {
    Route::post('/',[GuestController::class,'index']);
    Route::post('/show',[GuestController::class,'show']);
    Route::post('/store',[GuestController::class,'store']);
    Route::post('/update',[GuestController::class,'update']);
    Route::post('/delete',[GuestController::class,'destroy']);
});

Route::prefix('room')->group(function () {
    Route::post('/',[RoomController::class,'index']);
    Route::post('/show',[RoomController::class,'show']);
    Route::post('/store',[RoomController::class,'store']);
    Route::post('/update',[RoomController::class,'update']);
    Route::post('/delete',[RoomController::class,'destroy']);
});

Route::prefix('reservation')->group(function () {
   Route::post('/',[ReservationController::class,'index']);
   Route::post('/room',[ReservationController::class,'room']);
   Route::post('/guest',[ReservationController::class,'guest']);
   Route::post('/show',[ReservationController::class,'show']);
   Route::post('/store',[ReservationController::class,'store']);
   Route::post('/update',[ReservationController::class,'update']);
   Route::post('/delete',[ReservationController::class,'destroy']);
});
