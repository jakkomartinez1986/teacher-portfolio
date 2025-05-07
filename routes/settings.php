<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\Area\AreaController;
use App\Http\Controllers\Settings\School\YearController;
use App\Http\Controllers\Settings\Area\SubjectController;
use App\Http\Controllers\Settings\School\GradeController;
use App\Http\Controllers\Settings\School\NivelController;
use App\Http\Controllers\Settings\School\ShiftController;
use App\Http\Controllers\Settings\School\SchoolController;
use App\Http\Controllers\Settings\Trimester\TrimesterController;
use App\Http\Controllers\Settings\Document\DocumentTypeController;
use App\Http\Controllers\Settings\School\GradingSettingController;
use App\Http\Controllers\Settings\Document\DocumentCategoryController;

Route::resource('/areas', AreaController::class); 
Route::resource('/subjects', SubjectController::class); 
Route::post('subjects/{subject}/attach-document', [SubjectController::class, 'attachDocument'])
    ->name('subjects.attach-document');
    
Route::delete('subjects/{subject}/detach-document/{document}', [SubjectController::class, 'detachDocument'])
    ->name('subjects.detach-document');

    
Route::resource('/grades', GradeController::class); 
Route::resource('/grading-settings', GradingSettingController::class); 
Route::resource('/nivels', NivelController::class); 
Route::resource('/schools', SchoolController::class); 
Route::resource('/shifts', ShiftController::class); 
Route::resource('/years', YearController::class); 
Route::resource('/document-categories', DocumentCategoryController::class); 
Route::resource('/document-types', DocumentTypeController::class); 

Route::resource('/trimesters', TrimesterController::class); 
