<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\System\Student\StudentController;
Route::resource('/students', StudentController::class); 
Route::get('students/import/form/{grade}', [StudentController::class, 'showImportForm'])->name('students.import.form');
Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
Route::get('students/import/template', [StudentController::class, 'downloadTemplate'])->name('students.import.template');