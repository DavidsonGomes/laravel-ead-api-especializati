<?php

use App\Http\Controllers\Api\{
    CourseController,
    LessonController,
    ModuleController,
    ReplySupportController,
    SupportController
};
use App\Http\Controllers\Api\Auth\{
    AuthController,
    ResetPasswordController
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true
    ]);
});

/**
 * Auth
 */
// Login
Route::post('/auth', [AuthController::class, 'auth'])->name('login');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

// Me
Route::get('/profile', [AuthController::class, 'profile'])->name('profile')->middleware('auth:sanctum');

/**
 * Reset Password
 */
// Forgot Password
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLink'])->name('forgot-password')->middleware('guest');

/**
 * Authenticated
 */
Route::middleware(['auth:sanctum'])->group(function () {
    // Courses
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');

    // Modules
    Route::get('/courses/{id}/modules', [ModuleController::class, 'index'])->name('modules.index');

    // Lessons
    Route::get('/modules/{id}/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show');

    // Supports
    Route::get('/my-supports', [SupportController::class, 'mySupports'])->name('supports.my-supports');
    Route::get('/supports', [SupportController::class, 'index'])->name('supports.index');
    Route::post('/supports', [SupportController::class, 'store'])->name('supports.store');

    // Reply Supports
    Route::post('replies', [ReplySupportController::class, 'createReply'])->name('replies.create-reply');
});
