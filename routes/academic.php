<?php

use App\Http\Controllers\System\Teacher\ClassScheludeController;
use App\Http\Controllers\System\Teacher\TeacherScheduleController;
use Illuminate\Support\Facades\Route;
Route::resource('/class-schedules', ClassScheludeController::class); 
Route::resource('/teacher-schedule', TeacherScheduleController::class); 