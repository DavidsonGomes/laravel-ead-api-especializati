<?php

use App\Http\Controllers\Api\{
    CourseController,
    LessonController,
    ModuleController,
    SupportController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true
    ]);
});

// Courses
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');

// Modules
Route::get('/courses/{id}/modules', [ModuleController::class, 'index'])->name('modules.index');

// Lessons
Route::get('/modules/{id}/lessons', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show');

// Supports
Route::get('/supports', [SupportController::class, 'index'])->name('supports.index');
Route::post('/supports', [SupportController::class, 'store'])->name('supports.store');

// Reply Supports
Route::post('/supports/{id}/replies', [SupportController::class, 'createReply'])->name('supports.create-reply');
