<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\System\Attendance\AttendanceController;
use App\Http\Controllers\System\Teacher\ClassScheduleController;
use App\Http\Controllers\System\Teacher\AcademicGradingController;
use App\Http\Controllers\System\Teacher\TeacherScheduleController;
use App\Http\Controllers\System\Teacher\PerformanceSummaryController;
use App\Http\Controllers\System\Attendance\AttendanceDashboardController;
Route::resource('/class-schedules', ClassScheduleController::class); 
Route::resource('/teacher-schedule', TeacherScheduleController::class); 
Route::resource('/summary', PerformanceSummaryController::class); 
Route::resource('/attendance', AttendanceController::class); 
// Route::resource('/attendance-dashboard', AttendanceDashboardController::class);
Route::controller(AttendanceDashboardController::class)->group(function() {
    Route::get('/attendance-teacher-dashboard', 'teacherdashboard')
        ->name('attendance-dashboard-teacher');
    Route::get('/attendance-teacher-incident-dashboard', 'teacherincidentdashboard')
        ->name('attendance-dashboard-teacher-incident');
    
    Route::get('/attendance-tutor-dashboard', 'tutordashboard')
        ->name('attendance-dashboard-tutor');
    Route::get('/attendance-tutor-incident', 'tutorincidentdashboard')
        ->name('attendance-dashboard-incident-tutor');
});
Route::get('grading-summary', [AcademicGradingController::class, 'index'])->name('grading-summary.index');
 // Guardar calificaciones
Route::post('grading-summary', [AcademicGradingController::class, 'store'])->name('grading-summary.store');
// Importación/Exportación
Route::get('grading-summary/format', [AcademicGradingController::class, 'downloadTemplate'])->name('grading-summary.download-template');
Route::post('grading-summary/import', [AcademicGradingController::class, 'import'])->name('grading-summary.import');
Route::post('grading-summary/paste-excel', [AcademicGradingController::class, 'pasteFromExcel'])->name('grading-summary.paste-from-excel');

// Route::post('subjects/{subject}/attach-document', [SubjectController::class, 'attachDocument'])
//     ->name('subjects.attach-document');