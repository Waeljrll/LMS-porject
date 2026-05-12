@extends('layouts.app')

@section('front-content')
    <div class="pagetitle">
        <h1>Profile Details</h1>
        <nav>
            <ol class="breadcrumb">
                @php
                    $homeRoute = match (auth()->user()->role) {
                        'admin' => route('admin.dashboard'),
                        'instructor' => route('instructor.dashboard'),
                        default => route('student.dashboard'),
                    };
                @endphp
                <li class="breadcrumb-item"><a href="{{ $homeRoute }}">Home</a></li>
                <li class="breadcrumb-item active">My Profile</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ auth()->user()->imageUrl() }}" alt="Profile" class="rounded-circle"
                            style="width: 120px; height: 120px; object-fit: cover;">
                        <h2 class="mt-3">{{ auth()->user()->name }}</h2>
                        <h3 class="text-muted small">{{ ucfirst(auth()->user()->role) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <h5 class="card-title">Profile Information</h5>

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-primary fw-bold">Full Name</div>
                            <div class="col-lg-9 col-md-8 border-bottom pb-1">{{ auth()->user()->name }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-primary fw-bold">Email</div>
                            <div class="col-lg-9 col-md-8 border-bottom pb-1">{{ auth()->user()->email }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-primary fw-bold">Phone</div>
                            <div class="col-lg-9 col-md-8 border-bottom pb-1">{{ auth()->user()->phone ?? 'N/A' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-primary fw-bold">About (Bio)</div>
                            <div class="col-lg-9 col-md-8 border-bottom pb-1">
                                {{ auth()->user()->bio ?? 'No bio available' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-primary fw-bold">Account Status</div>
                            <div class="col-lg-9 col-md-8">
                                <span class="badge {{ auth()->user()->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst(auth()->user()->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square me-1"></i> Go to Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
