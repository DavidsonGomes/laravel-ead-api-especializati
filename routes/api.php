<?php

use App\Http\Controllers\Api\{
    CourseController,
    LessonController,
    ModuleController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true
    ]);
});

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');

Route::get('/courses/{id}/modules', [ModuleController::class, 'index'])->name('modules.index');

Route::get('/modules/{id}/lessons', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show');
