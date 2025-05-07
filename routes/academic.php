<?php

use App\Http\Controllers\System\Teacher\AcademicGradingController;
use App\Http\Controllers\System\Teacher\ClassScheludeController;
use App\Http\Controllers\System\Teacher\TeacherScheduleController;
use Illuminate\Support\Facades\Route;
Route::resource('/class-schedules', ClassScheludeController::class); 
Route::resource('/teacher-schedule', TeacherScheduleController::class); 
Route::get('grading-summary', [AcademicGradingController::class, 'index'])->name('grading-summary.index');
 // Guardar calificaciones
Route::post('grading-summary', [AcademicGradingController::class, 'store'])->name('grading-summary.store');
// Importación/Exportación
Route::get('grading-summary/format', [AcademicGradingController::class, 'downloadTemplate'])->name('grading-summary.download-template');
Route::post('grading-summary/import', [AcademicGradingController::class, 'import'])->name('grading-summary.import');
Route::post('grading-summary/paste-excel', [AcademicGradingController::class, 'pasteFromExcel'])->name('grading-summary.paste-from-excel');

// Route::post('subjects/{subject}/attach-document', [SubjectController::class, 'attachDocument'])
//     ->name('subjects.attach-document');