<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\IsTeacher;
use App\Http\Controllers\SessionController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function() {
      Route::post('logout', [AuthController::class, 'logout']);
      Route::get('user', [AuthController::class, 'user']);
    });
});




//Teacher's Routes
Route::middleware(['auth:sanctum','authTeacher'])->group(function () {
  
  //create session
  Route::post('/session',[SessionController::class,'create']);

  //get sessions
  Route::get('/session',[SessionController::class,'index']);
});

//Student's Routes
Route::middleware(['auth:sanctum','authStudent'])->group(function () {
  Route::post('/session/register',[SessionController::class,'register']);
});


