<?php

use App\Http\Controllers\SemesterController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

    
Route::middleware(['auth', 'verified'])->prefix('semesters')->group(function () {
    Route::get('/', [SemesterController::class, 'index'])->name('semesters.index');
    Route::get('/create', [SemesterController::class, 'create'])->name('semesters.create');
    Route::get('/edit/{id}', [SemesterController::class, 'edit'])->name('semesters.edit');
});

require __DIR__.'/auth.php';
