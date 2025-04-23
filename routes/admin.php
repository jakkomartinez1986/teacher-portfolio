<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Manager\User\UserController;
use App\Http\Controllers\Security\PermissionController;

Route::resource('/users', UserController::class); 
Route::resource('/roles', RoleController::class); 
Route::resource('/permissions', PermissionController::class); 
Route::post('/permissions/generate', [PermissionController::class, 'generate'])
->name('permissions.generate');
// Route::get('/users', function () {
//     return "users page";
// })->name('users.index');
//Route::resource('/roles', RoleController::class); 
//Route::resource('/permissions', PermissionController::class); 