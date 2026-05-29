<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.partials.side-bar', function ($view) {
            $user = Auth::user();
            $dashboardRoute = match ($user?->role) {
                'admin' => route('admin.dashboard'),
                'instructor' => route('instructor.dashboard'),
                'student' => route('student.dashboard'),
                default => route('dashboard'),
            };

            $view->with('dashboardRoute', $dashboardRoute);
        });
    }
}
