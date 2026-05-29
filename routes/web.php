<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboard;
use App\Http\Controllers\Instructor\CourseController as InstractorCourseController;
use App\Http\Controllers\Instructor\InstructorCategoryController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\Student\CourseController as StudentCourse;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Student\LearningController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PROFILE (ALL USERS)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        /** @var \App\Models\User $user */
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

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('courses', CourseController::class);
        Route::get('/courses/{course}/content', [CourseController::class, 'content'])->name('courses.content');

        Route::post('/courses/{course}/sections', [SectionController::class, 'storeSection'])->name('sections.store');
        Route::post('/sections/{section}/reorder/{direction}', [SectionController::class, 'reorderSection'])->name('sections.reorder');
        Route::delete('/sections/{section}', [SectionController::class, 'destroySection'])->name('sections.destroy');
        Route::put('/sections/{section}', [SectionController::class, 'updateSection'])->name('sections.update');

        Route::get('/sections/{section}/lessons/create', [LessonController::class, 'createLesson'])->name('lessons.create');
        Route::post('/sections/{section}/lessons', [LessonController::class, 'storeLesson'])->name('lessons.store');
        Route::get('/lessons/{lesson}/edit', [LessonController::class, 'editLesson'])->name('lessons.edit');
        Route::put('/lessons/{lesson}', [LessonController::class, 'updateLesson'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [LessonController::class, 'destroyLesson'])->name('lessons.destroy');
    });

/*
|--------------------------------------------------------------------------
| INSTRUCTOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:instructor'])
    ->prefix('instructor')
    ->name('instructor.')
    ->group(function () {
        Route::get('/dashboard', [InstructorDashboard::class, 'index'])->name('dashboard');
        Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('courses.my');
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::get('/courses/{course}/content', [CourseController::class, 'content'])->name('courses.content');
        Route::get('courses/{course}/analytics', [InstractorCourseController::class, 'analytics'])->name('courses.analytics');

        Route::post('/courses/{course}/sections', [SectionController::class, 'storeSection'])->name('sections.store');
        Route::put('/sections/{section}', [SectionController::class, 'updateSection'])->name('sections.update');
        Route::post('/sections/{section}/reorder/{direction}', [SectionController::class, 'reorderSection'])->name('sections.reorder');
        Route::delete('/sections/{section}', [SectionController::class, 'destroySection'])->name('sections.destroy');
        Route::get('/sections/{section}/lessons/create', [LessonController::class, 'createLesson'])->name('lessons.create');
        Route::post('/sections/{section}/lessons', [LessonController::class, 'storeLesson'])->name('lessons.store');
        Route::get('/lessons/{lesson}/edit', [LessonController::class, 'editLesson'])->name('lessons.edit');
        Route::put('/lessons/{lesson}', [LessonController::class, 'updateLesson'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [LessonController::class, 'destroyLesson'])->name('lessons.destroy');
    });

/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

    Route::get('courses', [StudentCourse::class, 'index'])->name('courses.index');
    Route::get('courses/{course}', [StudentCourse::class, 'show'])->name('courses.show');

    // الاشتراكات الخاصة بالطالب
    Route::get('my-courses', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('enrollments.store');

    Route::get('courses/{course}/learn/{lesson?}', [LearningController::class, 'show'])
        ->name('courses.learn')
        ->whereNumber('lesson');
});
// Route::middleware(['auth'])->get('/courses/{course}', [StudentCourse::class, 'show'])->name('courses.show');

require __DIR__ . '/auth.php';
