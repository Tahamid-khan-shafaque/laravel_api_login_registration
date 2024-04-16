<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;
//public route
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);
Route::post('/send_reset_email',[UserController::class,'send_reset_email']);
//protected route

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout',[UserController::class,'logout']);
});
Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/logged_user',[UserController::class,'logged_user']);
});
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/change_password',[UserController::class,'change_password']);
});
