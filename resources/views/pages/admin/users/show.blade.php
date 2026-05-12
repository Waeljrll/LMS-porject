@extends('layouts.app')

@section('front-content')
    <div class="pagetitle">
        <h1>User Details</h1>
    </div>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ $user->imageUrl() }}" alt="Profile" class="rounded-circle"
                            style="width: 120px; height: 120px; object-fit: cover;">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ ucfirst($user->role) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <h5 class="card-title">Profile Information</h5>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label">Full Name</div>
                            <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label">Email</div>
                            <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label">Status</div>
                            <div class="col-lg-9 col-md-8">
                                <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label">Joined At</div>
                            <div class="col-lg-9 col-md-8">{{ $user->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
