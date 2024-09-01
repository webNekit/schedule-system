<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
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


Route::middleware(['auth', 'verified'])->prefix('subjects')->group(function () {
    Route::get('/', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::get('edit/{id}', [SubjectController::class, 'edit'])->name('subjects.edit');
});

Route::middleware(['auth', 'verified'])->prefix('teachers')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::get('edit/{teacher}', [TeacherController::class, 'edit'])->name('teachers.edit');
});

Route::middleware(['auth', 'verified'])->prefix('rooms')->group(function () {
    Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('create', [RoomController::class, 'create'])->name('rooms.create');
    Route::get('edit/{room}', [RoomController::class, 'edit'])->name('rooms.edit');
});

require __DIR__.'/auth.php';
