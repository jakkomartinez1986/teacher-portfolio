<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\Document\DocumentSignatureController;


Route::resource('/documents', DocumentController::class); 
Route::post('/{document}/submit-review', [DocumentController::class, 'submitForReview'])->name('documents.submit-review');

// Firmas de documentos
//Route::prefix('document-signatures')->group(function () {
Route::post('/{signature}/approve', [DocumentSignatureController::class, 'approve'])->name('document-signatures.approve');
Route::post('/{signature}/reject', [DocumentSignatureController::class, 'reject'])->name('document-signatures.reject');

//});