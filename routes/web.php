<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleTableController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\WorkdayController;
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

Route::middleware(['auth', 'verified'])->prefix('departments')->group(function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::get('edit/{department}', [DepartmentController::class, 'edit'])->name('departments.edit');
});

Route::middleware(['auth', 'verified'])->prefix('groups')->group(function () {
    Route::get('/', [GroupController::class, 'index'])->name('groups.index');
    Route::get('create', [GroupController::class, 'create'])->name('groups.create');
    Route::get('edit/{group}', [GroupController::class, 'edit'])->name('groups.edit');
});

Route::middleware(['auth', 'verified'])->prefix('workdays')->group(function () {
    Route::get('/', [WorkdayController::class, 'index'])->name('workdays.index');
    Route::get('create', [WorkdayController::class, 'create'])->name('workdays.create');
    Route::get('edit/{workday}', [WorkdayController::class, 'edit'])->name('workdays.edit');
});


Route::middleware(['auth', 'verified'])->prefix('schedules')->group(function () {
    Route::get('/', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('create', [ScheduleController::class, 'create'])->name('schedules.create');
    // Route::get('edit/{schedule}', [ScheduleController::class, 'edit'])->name('schedules.edit');
    // В маршруте, используем идентификаторы, чтобы передать их в контроллер
    Route::get('edit/{schedule}/{departmentId}/{groupId}/{lessonId}', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::post('/transfer', [ScheduleController::class, 'transfer'])->name('schedules.transfer');

});

// Route::get('/preview', [ScheduleTableController::class, 'index'])->name('preview');
Route::get('/schedules/view/{workdayId}/{departmentId}', [ScheduleTableController::class, 'view'])->name('schedules.view');
// Route::get('/schedules/table', [ScheduleTableController::class, 'index'])->name('scheduleTable.index');



require __DIR__.'/auth.php';
