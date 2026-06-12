<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboard;
use App\Http\Controllers\Instructor\InstructorCategoryController;
use App\Http\Controllers\Instructor\Quizzes\QuizBuilderController;
use App\Http\Controllers\Instructor\Quizzes\QuizPreviewController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\Student\CourseController as StudentCourse;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Student\LearningController;
use App\Livewire\Student\QuizTaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC / AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isInstructor()) {
            return redirect()->route('instructor.dashboard');
        }
        return redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (Auth::user()->isAdmin()) return redirect()->route('admin.dashboard');
    if (Auth::user()->isInstructor()) return redirect()->route('instructor.dashboard');
    return redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| PROFILE (ALL AUTHENTICATED USERS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Users Management
        Route::resource('users', UserController::class);

        // Categories
        Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);

        // Courses
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::get('/courses/{course}/content', [CourseController::class, 'content'])->name('courses.content');

        // Sections (nested under courses)
        Route::post('/courses/{course}/sections', [SectionController::class, 'storeSection'])->name('sections.store');
        Route::post('/sections/{section}/reorder/{direction}', [SectionController::class, 'reorderSection'])
            ->name('sections.reorder')
            ->where('direction', 'up|down');
        Route::put('/sections/{section}', [SectionController::class, 'updateSection'])->name('sections.update');
        Route::delete('/sections/{section}', [SectionController::class, 'destroySection'])->name('sections.destroy');

        // Lessons (nested under sections)
        Route::get('/sections/{section}/lessons/create', [LessonController::class, 'createLesson'])->name('lessons.create');
        Route::post('/sections/{section}/lessons', [LessonController::class, 'storeLesson'])->name('lessons.store');
        Route::get('/lessons/{lesson}/edit', [LessonController::class, 'editLesson'])->name('lessons.edit');
        Route::put('/lessons/{lesson}', [LessonController::class, 'updateLesson'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [LessonController::class, 'destroyLesson'])->name('lessons.destroy');

        // Quiz Builder (Admin can also manage)
        Route::get('/sections/{section}/quizzes/builder', [QuizBuilderController::class, 'index'])->name('quizzes.builder');
        Route::get('/quizzes/{quiz}/preview', [QuizPreviewController::class, 'index'])->name('quizzes.preview');
    });

/*
|--------------------------------------------------------------------------
| INSTRUCTOR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:instructor'])
    ->prefix('instructor')
    ->name('instructor.')
    ->group(function () {

        Route::get('/dashboard', [InstructorDashboard::class, 'index'])->name('dashboard');

        // Courses
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/my', [CourseController::class, 'myCourses'])->name('courses.my');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::get('/courses/{course}/content', [CourseController::class, 'content'])->name('courses.content');
        Route::get('/courses/{course}/analytics', [InstructorCourseController::class, 'analytics'])->name('courses.analytics');

        // Sections
        Route::post('/courses/{course}/sections', [SectionController::class, 'storeSection'])->name('sections.store');
        Route::post('/sections/{section}/reorder/{direction}', [SectionController::class, 'reorderSection'])
            ->name('sections.reorder')
            ->where('direction', 'up|down');
        Route::put('/sections/{section}', [SectionController::class, 'updateSection'])->name('sections.update');
        Route::delete('/sections/{section}', [SectionController::class, 'destroySection'])->name('sections.destroy');

        // Lessons
        Route::get('/sections/{section}/lessons/create', [LessonController::class, 'createLesson'])->name('lessons.create');
        Route::post('/sections/{section}/lessons', [LessonController::class, 'storeLesson'])->name('lessons.store');
        Route::get('/lessons/{lesson}/edit', [LessonController::class, 'editLesson'])->name('lessons.edit');
        Route::put('/lessons/{lesson}', [LessonController::class, 'updateLesson'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [LessonController::class, 'destroyLesson'])->name('lessons.destroy');

        // Quiz Builder
        Route::get('/sections/{section}/quizzes/builder', [QuizBuilderController::class, 'index'])->name('quizzes.builder');
        Route::get('/quizzes/{quiz}/preview', [QuizPreviewController::class, 'index'])->name('quizzes.preview');

        // Categories (Browse only)
        Route::get('/categories', [InstructorCategoryController::class, 'index'])->name('categories.index');
    });

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

        // Browse & Enroll
        Route::get('/courses', [StudentCourse::class, 'index'])->name('courses.index');
        Route::get('/courses/{id}', [StudentCourse::class, 'show'])->name('courses.show');
        Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('enrollments.store');

        // My Learning
        Route::get('/my-courses', [EnrollmentController::class, 'index'])->name('enrollments.index');
        Route::get('/courses/{course}/learn/{lesson?}', [LearningController::class, 'show'])
            ->name('courses.learn');
        // Quiz Taker - Full Page Livewire Component with Route Model Binding
        Route::get('/quizzes/{quiz}/take', QuizTaker::class,)
            ->name('quizzes.take');
    });

/*
|--------------------------------------------------------------------------
| PUBLIC COURSE BROWSING (No auth required to view, auth required to enroll)
|--------------------------------------------------------------------------
*/
Route::get('/courses', [StudentCourse::class, 'index'])->name('public.courses.index');
Route::get('/courses/{id}', [StudentCourse::class, 'show'])->name('public.courses.show');
